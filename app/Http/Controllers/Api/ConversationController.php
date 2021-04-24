<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\ConversationMessage;
use App\EmailType;
use App\Facades\Helpers\StorageHelper;
use App\Http\Requests\Admin\Conversations\AddInvisibleImageToConversationRequest;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Http\Validators\BotUpdate;
use App\Mail\MessageReceived;
use App\Managers\ConversationManager;
use App\Managers\ConversationNoteManager;
use App\Managers\StorageManager;
use App\MessageAttachment;
use App\OpenConversationPartner;
use App\Services\ProbabilityService;
use App\Services\UserActivityService;
use App\User;
use App\UserImage;
use App\UserView;
use Carbon\Carbon;
use Config;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class ConversationController
 * @package App\Http\Controllers
 */
class ConversationController
{
    private $conversationManager;
    /**
     * @var ConversationNoteManager
     */
    private ConversationNoteManager $conversationNoteManager;
    /**
     * @var UserActivityService
     */
    private UserActivityService $userActivityService;
    /**
     * @var StorageManager
     */
    private StorageManager $storageManager;

    /**
     * ConversationController constructor.
     * @param ConversationManager $conversationManager
     */
    public function __construct(
        ConversationManager $conversationManager,
        ConversationNoteManager $conversationNoteManager,
        UserActivityService $userActivityService,
        StorageManager $storageManager
    ) {
        $this->conversationManager = $conversationManager;
        $this->conversationNoteManager = $conversationNoteManager;
        $this->userActivityService = $userActivityService;
        $this->storageManager = $storageManager;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), MessageCreateRequest::rules());

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        $requestingUser = $request->user();

        $messageData = $request->all();
        $messageData['operator_id'] = $requestingUser->getId();

        /** @var Conversation $conversation */
        $conversation  = $this->conversationManager->retrieveConversation(
            $messageData['sender_id'],
            $messageData['recipient_id']
        );

        if (
            (
                $conversation &&
                (
                    null === $conversation->getReplyableAt() ||
                    $conversation->getReplyableAt()->gt(Carbon::now()) ||
                    $conversation->getCycleStage() === Conversation::CYCLE_STAGE_BALL_IN_PEASANTS_COURT
                )
            ) &&
            !$requestingUser->isAdmin()
        ) {
            return response()->json(
                'The conversation is not replyable',
                403
            );
        }

        try {
            if (
                $conversation instanceof Conversation &&
                $conversation->getLockedByUserId() !== $requestingUser->getId() &&
                !$requestingUser->isAdmin()
            ) {
                \Log::error('User ID: ' . $requestingUser->getId() .
                    ' attempted to send message in conversation ID: ' . $conversation->getId() .
                    ', but conversation is locked by user ID: ' . $conversation->getLockedByUserId()
                );

                return response()->json(
                    'The conversation is locked by someone else',
                    403
                );
            } elseif (
                $conversation instanceof Conversation &&
                $conversation->getLockedByUserId() === $requestingUser->getId() &&
                $conversation->getLockedAt()->diffInMinutes(new Carbon()) > ConversationManager::CONVERSATION_LOCKING_TIME
            ) {
                return response()->json(
                    'You had locked the conversation for too long',
                    403
                );
            }

            $this->conversationManager->createMessage($messageData);

            /** @var User $recipient */
            $recipient = User::find($messageData['recipient_id']);

            /** @var User $sender */
            $sender = User::find($messageData['sender_id']);

            $recipientPartnerIds = OpenConversationPartner::where('user_id', $recipient->getId())
                ->get()
                ->pluck('partner_id')
                ->toArray();

            $recipientOpenConversationPartnersCount = count($recipientPartnerIds);

            if (!in_array($sender->getId(), $recipientPartnerIds) && $recipientOpenConversationPartnersCount < 2) {
                $recipient->addOpenConversationPartner($sender, 1);
            }

            $recipientEmailTypeIds = $recipient->emailTypes->pluck('id')->toArray();

            $recipientHasMessageNotificationsEnabled = in_array(
                EmailType::MESSAGE_RECEIVED,
                $recipientEmailTypeIds
            );

            if ($recipientHasMessageNotificationsEnabled) {
                $onlineIds = $this->userActivityService->getOnlineUserIds(
                    $this->userActivityService::PEASANT_MAILING_ONLINE_TIMEFRAME_IN_MINUTES
                );

                if (!in_array($recipient->getId(), $onlineIds)) {
                    if (config('app.env') === 'production') {
                        $hasAttachment = isset($messageData['attachment']) && $messageData['attachment'] ? true : false;
                        $message = isset($messageData['message']) && $messageData['message'] ? $messageData['message'] : null;

                        $messageReceivedEmail = (new MessageReceived(
                            $sender,
                            $recipient,
                            $message,
                            $hasAttachment
                        ))->onQueue('emails');

                        Mail::to($recipient)
                            ->queue($messageReceivedEmail);
                    }

                    $recipient->emailTypeInstances()->attach(EmailType::MESSAGE_RECEIVED, [
                        'email' => $recipient->getEmail(),
                        'email_type_id' => EmailType::MESSAGE_RECEIVED,
                        'actor_id' => $sender->getId()
                    ]);
                }
            }

            $sender = User::find($messageData['sender_id']);

            if ($sender->isBot()) {
                $sender->setLastOnlineAt(
                    Carbon::now('Europe/Amsterdam')->setTimezone('UTC')
                );

                $sender->save();

                if (!is_object($conversation->messages)) {
                    \Log::error('Sender ID: ' . $sender->getId());
                    \Log::error('Message body: ' . $messageData['message']);
                }

                // Sometimes pretend that the bot viewed the user's profile. Always when it is the first time they chat
                if ($conversation->messages->count() == 1 || rand(0, 1)) {
                    $userViewInstance = new UserView();
                    $userViewInstance->setViewerId($messageData['sender_id']);
                    $userViewInstance->setViewedId($messageData['recipient_id']);
                    $userViewInstance->setType(UserView::TYPE_OPERATOR_MESSAGE);
                    $userViewInstance->save();
                }
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            if (Str::contains($exception->getMessage(), 'message') && $conversation) {
                \Log::error('Convo problem convo ID:' . $conversation->getId());
            }

            return response()->json('The message was an error', 500);
        }

        if ($conversation instanceof Conversation) {
            /** @var Conversation $conversation */
            $conversation->setLockedByUserId(null);
            $conversation->setLockedAt(null);
            $conversation->save();
        }

        return response()->json('The message was sent successfully');
    }

    public function addInvisibleImage(AddInvisibleImageToConversationRequest $request)
    {
        $validator = Validator::make($request->all(), AddInvisibleImageToConversationRequest::rules());

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }


        $requestingUser = $request->user();

        /** @var integer $conversationId */
        $conversationId = (int) $request->get('conversation_id');

        DB::beginTransaction();

        try {

            /** @var User $sender */
            $sender = User::findOrFail($request->get('sender_id'));

            /** @var User $recipient */
            $recipient = User::findOrFail($request->get('recipient_id'));

            /** @var Conversation $conversation */
            $conversation  = $this->conversationManager->createOrRetrieveConversation(
                $sender,
                $recipient
            );

            if (
                (
                    $conversation &&
                    (
                        null === $conversation->getReplyableAt() ||
                        $conversation->getReplyableAt()->gt(Carbon::now()) ||
                        $conversation->getCycleStage() === Conversation::CYCLE_STAGE_BALL_IN_PEASANTS_COURT
                    )
                ) &&
                !$requestingUser->isAdmin()
            ) {
                return response()->json(
                    'The conversation is not replyable',
                    403
                );
            }

            if (
                $conversation instanceof Conversation &&
                $conversation->getLockedByUserId() !== $requestingUser->getId() &&
                !$requestingUser->isAdmin()
            ) {
                \Log::error('User ID: ' . $requestingUser->getId() .
                    ' attempted to send invisible image in conversation ID: ' . $conversation->getId() .
                    ', but conversation is locked by user ID: ' . $conversation->getLockedByUserId()
                );

                return response()->json(
                    'The conversation is locked by someone else',
                    403
                );
            } elseif (
                $conversation->getLockedByUserId() === $requestingUser->getId() &&
                $conversation->getLockedAt()->diffInMinutes(new Carbon()) > ConversationManager::CONVERSATION_LOCKING_TIME
            ) {
                return response()->json(
                    'You had locked the conversation for too long',
                    403
                );
            }

            if ($conversation->messages->count() > 2) {
                $conversation->setCycleStage(
                    Conversation::CYCLE_STAGE_BALL_IN_PEASANTS_COURT
                );

                if (
                    $conversation->messages[0]->sender->roles[0]->id === User::TYPE_PEASANT
                ) {
                    if (ProbabilityService::getTrueAPercentageOfTheTime(30)) {
                        $conversation->setReplyableAt(null);
                    } else {
                        $conversation->setCycleStage(
                            Conversation::CYCLE_STAGE_STOPPED
                        );

                        $conversation->setReplyableAt(Carbon::now()->addDays(ConversationManager::CONVERSATION_PRE_STOPPED_PERIOD_IN_DAYS));
                    }
                }

                if (
                    $conversation->messages[0]->sender->roles[0]->id === User::TYPE_BOT
                ) {
                    $operatorMessageType = ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED;
                }
            } elseif ($sender->isBot()) {
                $conversation->setReplyableAt(null);
            }

            if ($sender->isBot()) {
                $sender->setLastOnlineAt(
                    Carbon::now('Europe/Amsterdam')->setTimezone('UTC')
                );

                $sender->save();
            }

            $body = $request->get('body') ?? null;

            /** @var UserImage $image */
            $image = UserImage::find($request->get('image_id'));

            $message = new ConversationMessage();
            $message->setSenderId($sender->getId());
            $message->setRecipientId($recipient->getId());
            $message->setHasAttachment(true);
            $message->setConversationId($conversation->id);
            $message->setType('generic');
            $message->setBody($body);
            $message->save();
            $message->setOperatorId($requestingUser->getId());
            $message->setOperatorMessageType(isset($operatorMessageType) ? $operatorMessageType : null);

            $fileExists = $this->storageManager->fileExists(
                $image->getFilename(),
                StorageHelper::userImagesPath($sender->getId()),
                'cloud'
            );

            if (!$fileExists) {
                throw new \Exception('File does not exist');
            }

            $existingFilepath = StorageHelper::userImagesPath($sender->getId()) . $image->getFilename();

            $fileExtension = explode('.', $image->getFilename())[1];
            $filenameWithoutExtension = explode('.', $image->getFilename())[0];

            $thumbFilename = $filenameWithoutExtension . '_thumb';

            $existingThumbFilepath = StorageHelper::userImagesPath($sender->getId()) . $thumbFilename . '.' . $fileExtension;

            $messageAttachmentsPath = StorageHelper::messageAttachmentsPath($conversation->id);

            $newFilenameWithoutExtension = md5(microtime() . $filenameWithoutExtension);
            $newFilename = $newFilenameWithoutExtension . '.' . $fileExtension;
            $newThumbFilename = $newFilenameWithoutExtension . '_thumb.' . $fileExtension;
            $newFilepath = $messageAttachmentsPath . $newFilename;
            $newThumbFilepath = $messageAttachmentsPath . $newThumbFilename;

            $copy = Storage::disk('cloud')->copy(
                $existingFilepath,
                $newFilepath
            );

            Storage::disk('cloud')->copy(
                $existingThumbFilepath,
                $newThumbFilepath
            );

            $messageAttachment = new MessageAttachment();
            $messageAttachment->setConversationId($conversation->id);
            $messageAttachment->setFilename($newFilename);
            $message->attachment()->save($messageAttachment);

            $recipientPartnerIds = OpenConversationPartner::where('user_id', $recipient->getId())
                ->get()
                ->pluck('partner_id')
                ->toArray();

            if (!in_array($sender->getId(), $recipientPartnerIds) && count($recipientPartnerIds) < 2) {
                $recipient->addOpenConversationPartner($sender, 1);
            }

            $recipientEmailTypeIds = $recipient->emailTypes->pluck('id')->toArray();

            $recipientHasMessageNotificationsEnabled = in_array(
                EmailType::MESSAGE_RECEIVED,
                $recipientEmailTypeIds
            );

            if ($recipientHasMessageNotificationsEnabled) {
                $onlineIds = $this->userActivityService->getOnlineUserIds(
                    $this->userActivityService::PEASANT_MAILING_ONLINE_TIMEFRAME_IN_MINUTES
                );

                if (!in_array($recipient->getId(), $onlineIds)) {
                    if (config('app.env') === 'production') {
                        $hasAttachment = true;
                        $message = $body ? $body : null;

                        $messageReceivedEmail = (new MessageReceived(
                            $sender,
                            $recipient,
                            $message,
                            $hasAttachment
                        ))->onQueue('emails');

                        Mail::to($recipient)
                            ->queue($messageReceivedEmail);
                    }

                    $recipient->emailTypeInstances()->attach(EmailType::MESSAGE_RECEIVED, [
                        'email' => $recipient->getEmail(),
                        'email_type_id' => EmailType::MESSAGE_RECEIVED,
                        'actor_id' => $sender->getId()
                    ]);
                }
            }

            if ($conversation instanceof Conversation) {
                /** @var Conversation $conversation */
                $conversation->setLockedByUserId(null);
                $conversation->setLockedAt(null);
                $conversation->save();
            }

            DB::commit();

            return response()->json('The message was sent successfully');
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json('The message was not sent due to an exception - ' . $exception->getMessage(), 500);
        }
    }

    public function getLockedInformation($conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::findOrFail($conversationId);

        $response = [
            'locked_by_user_id' => $conversation->getLockedByUserId(),
            'locked_at' => $conversation->getLockedAt()
        ];

        return response()->json($response, 200);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getChatTranslations(int $userId) {
        $chatTranslations = File::getRequire(base_path() . '/resources/lang/' . User::find($userId)->getLocale() . '/' . config('app.directory_name') . '/chat.php');

        return response()->json($chatTranslations, 200);
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $offset
     * @param int $limit
     * @return JsonResponse
     */
    public function getConversationByParticipantIds(
        int $userAId,
        int $userBId,
        int $offset = 0,
        int $limit = 0
    ) {
        try {
            $conversation = $this->conversationManager
                ->retrieveConversation(
                    $userAId,
                    $userBId,
                    $offset,
                    $limit
                );

            if (!($conversation instanceof Conversation)) {
                return response()->json('No conversation exists between these users', 404);
            }

            return response()->json($conversation);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $messageIdHigherThan
     * @return JsonResponse
     */
    public function getConversationMessagesWithIdHigherThanByParticipantIds(
        int $userAId,
        int $userBId,
        int $messageIdHigherThan
    ) {
        try {
            $messages = $this->conversationManager
                ->getConversationMessagesWithIdHigherThanByParticipantIds(
                    $userAId,
                    $userBId,
                    $messageIdHigherThan
                );

            return response()->json($messages);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getHighestConversationId()
    {
        try {
            $conversationId = $this->conversationManager->getHighestConversationId();

            return response()->json($conversationId);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getConversationsByUserId(int $userId)
    {
        try {
            $conversations = $this->conversationManager->getConversationsByUserId($userId);

            return response()->json($conversations);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $userId
     * @param bool $value
     * @return JsonResponse
     */
    public function setConversationActivityForUserId(int $userAId, int $userBId, int $userId, bool $value)
    {
        try {
            $conversation = $this->conversationManager->retrieveConversation($userAId, $userBId);

            if (!($conversation instanceof Conversation)) {
                return response()->json('No conversation exists between these users', 404);
            }

            if ($conversation->getUserAId() === $userId) {
                $conversation->setNewActivityForUserA($value);
            } else {
                $conversation->setNewActivityForUserB($value);
            }

            $conversation->timestamps = false;
            $conversation->save();
            $conversation->timestamps = true;

            return response()->json($conversation);
        } catch (\Exception $exception) {
            Log::error($exception->getTraceAsString());

            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @param string $state
     * @return JsonResponse
     */
    public function persistConversationManagerState(int $userId, string $state)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            $user->setConversationManagerState($state);
            $user->save();

            return response()->json($state, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getConversationManagerState(int $userId)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            if (!($user instanceof User)) {
                \Log::debug('User with ID: ' . $userId . ' does not exist.');
            }

            return response()->json($user->getConversationManagerState(), 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @param int $partnerId
     * @param bool $state
     * @return JsonResponse
     */
    public function persistConversationPartnerId(int $userId, int $partnerId, bool $state)
    {
        try {
            $exists = OpenConversationPartner::where('user_id', $userId)
                    ->where('partner_id', $partnerId)
                    ->count() > 0;

            /** @var User $user */
            $user = User::find($userId);

            if ($exists) {
                $user->openConversationPartners()->updateExistingPivot($partnerId, ['state' => $state]);
            } else {
                $partner = User::find($partnerId);
                $user->addOpenConversationPartner($partner, $state);
            }

            return response()->json($user->openConversationPartners()->allRelatedIds(), 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getOpenConversationPartners(int $userId)
    {
        try {
            $partners = OpenConversationPartner::where('user_id', $userId)
                ->where('created_at', '<=', Carbon::now())
                ->orderBy('created_at', 'asc')
                ->get();

            $return = [];

            /** @var User $partner */
            foreach ($partners as $partner) {
                array_push($return, $partner->partner_id . ':' . $partner->state);
            }

            return response()->json($return);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getOperatorPlatformDashboardData()
    {
        try {
            $return = [
                'newConversations' => $this->conversationManager->getConversationsByCycleStage(
                    [Conversation::CYCLE_STAGE_NEW],
                    10,
                    false
                ),
                'unrepliedConversations' => $this->conversationManager->getConversationsByCycleStage(
                    [Conversation::CYCLE_STAGE_UNREPLIED],
                    10,
                    false
                ),
                'stoppedConversations' => $this->conversationManager->getConversationsByCycleStage(
                    [Conversation::CYCLE_STAGE_STOPPED],
                    10,
                    false
                ),
            ];

            return response()->json($return);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }


    /**
     * @return JsonResponse
     */
    public function lockAndGetData(
        Request $request,
        int $conversationId,
        $limit = 10
    ) {
        try {
            /** @var User $requestingUser */
            $requestingUser = $request->user();

            /** @var Conversation $conversation */
            $conversation = $this->conversationManager->getLeanConversationForOperatorView(
                $conversationId,
                $limit
            );

            $idsOfLockedConvos = $requestingUser->lockedConversations->pluck('id')->toArray();

            if (
                !$requestingUser->isAdmin() &&
                !in_array($conversation->getId(), $idsOfLockedConvos) &&
                $requestingUser->lockedConversations->count() > 1
            ) {
                return response()->json(
                    'You already have two conversations locked in your name',
                    403
                );
            }

            if (
                (
                    null === $conversation->getReplyableAt() ||
                    $conversation->getReplyableAt()->gt(Carbon::now()) ||
                    $conversation->getCycleStage() === Conversation::CYCLE_STAGE_BALL_IN_PEASANTS_COURT
                ) &&
                !$requestingUser->isAdmin()
            ) {
                return response()->json(
                    'The conversation is not replyable',
                    403
                );
            }

            if ($conversation->getLockedByUserId()) {
                if ($requestingUser->isAdmin()) {
                    $lockedByUserId = $conversation->getLockedByUserId();
                }

                $minutesAgo = (new Carbon('now'))->subMinutes(ConversationManager::CONVERSATION_LOCKING_TIME);

                if ($conversation->getLockedByUserId() === $requestingUser->getId()) {
                    if ($conversation->getLockedAt() < $minutesAgo) {
                        if (!$requestingUser->isAdmin()) {
                            $conversation->setLockedByUserId(null);
                            $conversation->setLockedAt(null);
                            $conversation->save();

                            return response()->json(
                                'You had locked the conversation for too long',
                                403
                            );
                        } else {
                            $conversation->setLockedByUserId($requestingUser->getId());
                            $conversation->setLockedAt(new Carbon('now'));
                            $conversation->save();
                        }
                    }
                } else {
                    if ($conversation->getLockedAt() < $minutesAgo) {
                        $conversation->setLockedByUserId($requestingUser->getId());
                        $conversation->setLockedAt(new Carbon('now'));
                        $conversation->save();
                    } else {
                        return response()->json(
                            'The conversation is locked by someone else',
                            403
                        );
                    }
                }
            } else {
                $conversation->setLockedByUserId($requestingUser->getId());
                $conversation->setLockedAt(new Carbon('now'));
                $conversation->save();
            }

            [$userANotes, $userBNotes] = $this->conversationNoteManager->getParticipantNotes($conversation);

            $userAttachments = $this->conversationManager->getAttachments(
                $conversationId,
                User::TYPE_PEASANT
            );


            $return = [
                'conversation' => $conversation,
                'userANotes' => $userANotes,
                'userBNotes' => $userBNotes,
                'lockedAt' => $conversation->getLockedAt()->tz('Europe/Amsterdam'),
                'now' => Carbon::now()->tz('Europe/Amsterdam'),
                'hasCountdown' => true,
                'userAttachments' => $userAttachments,
                'createdAtDiffForHumans' => $conversation->getCreatedAt()->diffForHumans()
            ];

            if (isset($lockedByUserId) && $requestingUser->getId() !== $lockedByUserId) {
                $return['lockedByUserId'] = $lockedByUserId;
                $return['lockedByUser'] = User::find($lockedByUserId);
            }

            return response()->json($return);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json($exception->getTraceAsString(), 500);
        }
    }

    /**
     * @param int $userId
     * @param int $partnerId
     * @return JsonResponse
     */
    public function removeConversationPartnerId(int $userId, int $partnerId)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            $user->removeOpenConversationPartner($partnerId);

            return response()->json($user->openConversationPartners()->allRelatedIds(), 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $conversationId
     * @return JsonResponse
     */
    public function deleteConversationById(int $conversationId) {
        try {
            /** @var Conversation $conversation */
            $conversation = Conversation::find($conversationId);

            if (is_null($conversation)) {
                throw new \Exception('The conversation does not exist');
            }

/*            if (\Auth::user()->getId() != $conversation->userA()->getId() && \Auth::user()->getId() != $conversation->userB()->getId()) {
                throw new \Exception('The user attempting to delete the conversation is not a participant of the conversation');
            }*/

            return response()->json(Conversation::destroy($conversationId), 200);
        } catch (\Exception $exception) {
            \Log::debug($exception->getMessage());
            return response()->json($exception->getMessage(), 500);
        }
    }
}
