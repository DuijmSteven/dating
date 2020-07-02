<?php

namespace App\Http\Controllers\Admin;

use App\Conversation;
use App\ConversationMessage;
use App\ConversationNote;
use App\EmailType;
use App\Facades\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Conversations\AddInvisibleImageToConversationRequest;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Mail\MessageReceived;
use App\Managers\ConversationManager;
use App\Managers\StorageManager;
use App\MessageAttachment;
use App\OpenConversationPartner;
use App\Services\ProbabilityService;
use App\User;
use App\UserImage;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kim\Activity\Activity;

/**
 * Class ConversationController
 * @package App\Http\Controllers\Admin
 */
class ConversationController extends Controller
{
    /** @var ConversationManager */
    private $conversationManager;

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
        StorageManager $storageManager
    ) {
        $this->conversationManager = $conversationManager;
        parent::__construct();
        $this->storageManager = $storageManager;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $conversations = Conversation::with([
            'userA',
            'userA.roles',
            'userA.meta',
            'userA.profileImage',
            'userB',
            'userB.roles',
            'userB.meta',
            'userB.profileImage'
        ])
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.conversations.overview',
            [
                'title' => 'Conversations Overview - ' . \config('app.name'),
                'headingLarge' => 'Conversations',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'conversations' => $conversations
            ]
        );
    }

    /**
     * @param $peasantId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofPeasantId($peasantId)
    {
        $conversations = Conversation::with([
            'userA',
            'userA.roles',
            'userA.meta',
            'userA.profileImage',
            'userB',
            'userB.roles',
            'userB.meta',
            'userB.profileImage'
        ])
            ->where('user_a_id', $peasantId)
            ->orWhere('user_b_id', $peasantId)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $peasant */
        $peasant = User::find($peasantId);

        return view(
            'admin.conversations.overview',
            [
                'title' => 'Conversations of peasant ID: ' . $peasant->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Conversations of peasant',
                'headingSmall' => $peasant->getUsername() . ' - (ID: ' . $peasant->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'conversations' => $conversations
            ]
        );
    }

    public function ofBotId($botId)
    {
        $conversations = Conversation::with([
            'userA',
            'userA.roles',
            'userA.meta',
            'userA.profileImage',
            'userB',
            'userB.roles',
            'userB.meta',
            'userB.profileImage'
        ])
            ->where('user_a_id', $botId)
            ->orWhere('user_b_id', $botId)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $bot */
        $bot = User::find($botId);

        return view(
            'admin.conversations.overview',
            [
                'title' => 'Conversations of bot ID: ' . $bot->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Conversations of bot',
                'headingSmall' => $bot->getUsername() . ' - (ID: ' . $bot->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'conversations' => $conversations
            ]
        );
    }

    /**
     * @param $peasantId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withOperatorId($operatorId)
    {
        $conversations = Conversation::with([
            'userA',
            'userA.roles',
            'userA.meta',
            'userA.profileImage',
            'userB',
            'userB.roles',
            'userB.meta',
            'userB.profileImage'
        ])
            ->with(['messages' => function ($query) {
                $query->withTrashed();
            }])
            ->whereHas('messages', function ($query) use ($operatorId) {
                $query->where('operator_id', $operatorId);
            })
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $peasant */
        $operator = User::find($operatorId);

        return view(
            'admin.conversations.overview',
            [
                'title' => 'Conversations with operator ID: ' . $operator->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Conversations with operator',
                'headingSmall' => $operator->getUsername() . ' - (ID: ' . $operator->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'conversations' => $conversations
            ]
        );
    }

    /**
     * @param $conversationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlock($conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::findOrFail($conversationId);
        $conversation->setLockedByUserId(null);
        $conversation->setLockedAt(null);
        $conversation->save();

        $alerts[] = [
            'type' => 'success',
            'message' => 'The conversation was unlocked'
        ];

        return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
    }

    /**
     * @param $conversationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUnreplyable($conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::findOrFail($conversationId);
        $conversation->setReplyableAt(null);
        $conversation->save();

        $alerts[] = [
            'type' => 'success',
            'message' => 'The conversation\'s (ID: ' . $conversationId .  ') replyable_at field was set to null'
        ];

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param int $conversationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(int $conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::with(['userA', 'userB', 'messages'])->withTrashed()->find($conversationId);

        if (
           !$this->authenticatedUser->isAdmin() &&
            $this->authenticatedUser->lockedConversations->count() > 1
        ) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'You already have two conversations locked in your name.'
            ];

            return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
        }

        if (
            (
                null === $conversation->getReplyableAt() ||
                $conversation->getReplyableAt()->gt(Carbon::now()) ||
                $conversation->getCycleStage() === Conversation::CYCLE_STAGE_BALL_IN_PEASANTS_COURT
            ) &&
            !$this->authenticatedUser->isAdmin()
        ) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'The conversation is not replyable'
            ];

            return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
        }

        if ($conversation->getLockedByUserId()) {
            if ($this->authenticatedUser->isAdmin()) {
                $lockedByUserId = $conversation->getLockedByUserId();
            }

            $minutesAgo = (new Carbon('now'))->subMinutes(ConversationManager::CONVERSATION_LOCKING_TIME);

            if ($conversation->getLockedByUserId() === $this->authenticatedUser->getId()) {
                if ($conversation->getLockedAt() < $minutesAgo) {
                    if (!$this->authenticatedUser->isAdmin()) {
                        $conversation->setLockedByUserId(null);
                        $conversation->setLockedAt(null);
                        $conversation->save();

                        $alerts[] = [
                            'type' => 'warning',
                            'message' => 'You had locked the conversation for too long.'
                        ];

                        return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
                    } else {
                        $conversation->setLockedByUserId($this->authenticatedUser->getId());
                        $conversation->setLockedAt(new Carbon('now'));
                        $conversation->save();
                    }
                }
            } else {
                if ($conversation->getLockedAt() < $minutesAgo) {
                    $conversation->setLockedByUserId($this->authenticatedUser->getId());
                    $conversation->setLockedAt(new Carbon('now'));
                    $conversation->save();
                } else {
                    $alerts[] = [
                        'type' => 'warning',
                        'message' => 'The conversation is locked by someone else'
                    ];

                    return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
                }
            }
        } else {
            $conversation->setLockedByUserId($this->authenticatedUser->getId());
            $conversation->setLockedAt(new Carbon('now'));
            $conversation->save();
        }

        $conversation = $this->prepareConversationObject($conversation);

        [$userANotes, $userBNotes] = $this->getParticipantNotes($conversation);

        $viewData = [
            'title' => 'Conversation (id: ' . $conversationId . ') - ' . \config('app.name'),
            'headingLarge' => 'Conversation (ID: ' . $conversationId . ')',
            'headingSmall' => $conversation->userA->username .
                ' (id: ' . $conversation->userA->id . ') - ' .
                $conversation->userB->username .
                ' (id:' . $conversation->userB->id . ')'  .
                ' - Gestart op: ' . $conversation->getCreatedAt()->diffForHumans(),
            'carbonNow' => Carbon::now('Europe/Amsterdam'),
            'conversation' => $conversation,
            'userANotes' => $userANotes,
            'userBNotes' => $userBNotes,
            'lockedAt' => $conversation->getLockedAt()->tz('Europe/Amsterdam'),
            'hasCountdown' => true
        ];

        if (isset($lockedByUserId) && $this->authenticatedUser->getId() !== $lockedByUserId) {
            $viewData['lockedByUserId'] = $lockedByUserId;
            $viewData['lockedByUser'] = User::find($lockedByUserId);
        }

        return view(
            'admin.conversations.show',
            $viewData
        );
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showNew(int $userAId, int $userBId)
    {
        if ($this->conversationManager->retrieveConversation($userAId, $userBId)) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The conversation already exists'
            ];

            return redirect()->back('alerts', $alerts);
        }

        $userA = User::find($userAId);
        $userB = User::find($userBId);

        /** @var Conversation $conversation */
        $conversation = new Conversation();
        $conversation->userA = $userA;
        $conversation->userB = $userB;

        $conversation = $this->prepareConversationObject($conversation);

        return view(
            'admin.conversations.show',
            [
                'title' => 'New conversation - ' . \config('app.name'),
                'headingLarge' => 'New conversation',
                'headingSmall' => $conversation->userA->username .
                    ' (id: ' . $conversation->userA->id . ') - ' .
                    $conversation->userB->username .
                    ' (id:' . $conversation->userB->id . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'conversation' => $conversation,
                'userANotes' => collect([]),
                'userBNotes' => collect([]),
            ]
        );
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @throws \Exception
     */
    public function checkIfConversationExists($userAId, $userBId) {
        $conversation = Conversation::where('user_a_id', (int) $userAId)
            ->where('user_b_id', (int) $userBId)
            ->orWhere(function ($query) use ($userAId, $userBId) {
                $query->where('user_a_id', $userBId);
                $query->where('user_b_id', $userAId);
            })
            ->first();

        if (!($conversation instanceof Conversation)) {
            return redirect()
                ->route(
                    'operator-platform.new-conversation.show',
                    [
                        'userAId' => $userAId,
                        'userBId' => $userBId,
                    ]
                );
        }

        return redirect()->route('operator-platform.conversations.show', [$conversation->getId()]);
    }

    /**
     * @param AddInvisibleImageToConversationRequest $request
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function addInvisibleImageToConversation(AddInvisibleImageToConversationRequest $request)
    {
        /** @var integer $conversationId */
        $conversationId = (int) $request->get('conversation_id');

        /** @var User $sender */
        $sender = User::find($request->get('sender_id'));

        /** @var User $recipient */
        $recipient = User::find($request->get('recipient_id'));

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
            !$this->authenticatedUser->isAdmin()
        ) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'The conversation is not replyable'
            ];

            return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
        }

        if (
            $conversation instanceof Conversation && 
            $conversation->getLockedByUserId() !== $this->authenticatedUser->getId() &&
            !$this->authenticatedUser->isAdmin()
        ) {
            \Log::error('User ID: ' . $this->authenticatedUser->getId() .
                ' attempted to send invisible image in conversation ID: ' . $conversation->getId() .
                ', but conversation is locked by user ID: ' . $conversation->getLockedByUserId()
            );

            $alerts[] = [
                'type' => 'error',
                'message' => 'The conversation is locked by someone else'
            ];

            return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
        } elseif (
            $conversation->getLockedByUserId() === $this->authenticatedUser->getId() &&
            $conversation->getLockedAt()->diffInMinutes(new Carbon()) > ConversationManager::CONVERSATION_LOCKING_TIME
        ) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'You had locked the conversation for too long'
            ];

            return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
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
        $message->setOperatorId($this->authenticatedUser->getId());
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

        if (!in_array($sender->getId(), $recipientPartnerIds)) {
            $recipient->addOpenConversationPartner($sender, 1);
        }

        $recipientEmailTypeIds = $recipient->emailTypes->pluck('id')->toArray();

        $recipientHasMessageNotificationsEnabled = in_array(
            EmailType::MESSAGE_RECEIVED,
            $recipientEmailTypeIds
        );

        if (
            $recipientHasMessageNotificationsEnabled &&
            $recipient->isMailable
        ) {
            $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();

            if (!in_array($recipient->getId(), $onlineUserIds)) {
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

        $alerts[] = [
            'type' => 'success',
            'message' => 'The message was sent successfully'
        ];

        return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
    }

    /**
     * @param MessageCreateRequest $messageCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MessageCreateRequest $messageCreateRequest)
    {
        $messageData = $messageCreateRequest->all();
        $messageData['operator_id'] = $this->authenticatedUser->getId();

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
            !$this->authenticatedUser->isAdmin()
        ) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'The conversation is not replyable'
            ];

            return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
        }

        try {
            if (
                $conversation instanceof Conversation &&
                $conversation->getLockedByUserId() !== $this->authenticatedUser->getId() &&
                !$this->authenticatedUser->isAdmin()
            ) {
                \Log::error('User ID: ' . $this->authenticatedUser->getId() .
                    ' attempted to send message in conversation ID: ' . $conversation->getId() .
                    ', but conversation is locked by user ID: ' . $conversation->getLockedByUserId()
                );

                $alerts[] = [
                    'type' => 'error',
                    'message' => 'The conversation is locked by someone else'
                ];

                return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
            } elseif (
                $conversation instanceof Conversation &&
                $conversation->getLockedByUserId() === $this->authenticatedUser->getId() &&
                $conversation->getLockedAt()->diffInMinutes(new Carbon()) > ConversationManager::CONVERSATION_LOCKING_TIME
            ) {
                $alerts[] = [
                    'type' => 'error',
                    'message' => 'You had locked the conversation for too long'
                ];

                return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
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

            if (!in_array($sender->getId(), $recipientPartnerIds) && $recipientOpenConversationPartnersCount < 4) {
                $recipient->addOpenConversationPartner($sender, 1);
            }

            $recipientEmailTypeIds = $recipient->emailTypes->pluck('id')->toArray();

            $recipientHasMessageNotificationsEnabled = in_array(
                EmailType::MESSAGE_RECEIVED,
                $recipientEmailTypeIds
            );

            if (
                $recipientHasMessageNotificationsEnabled &&
                $recipient->isMailable
            ) {
                $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();

                if (!in_array($recipient->getId(), $onlineUserIds)) {
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

            if ($sender->roles()->get()[0]->id === User::TYPE_BOT) {
                $activity = new Activity;
                $activity->id = bcrypt((int) time() . $messageData['sender_id'] . $messageData['recipient_id']);
                $activity->last_activity = time();
                $activity->user_id = $sender->getId();
                $activity->save();

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

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was sent successfully'
            ];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());


            if (Str::contains($exception->getMessage(), 'message') && $conversation) {
                \Log::error('Convo problem convo ID:' . $conversation->getId());
            }

            $alerts[] = [
                'type' => 'error',
                'message' => $exception->getMessage()
            ];
        }

        if ($conversation instanceof Conversation) {
            /** @var Conversation $conversation */
            $conversation->setLockedByUserId(null);
            $conversation->setLockedAt(null);
            $conversation->save();
        }

        return redirect()->route('operator-platform.dashboard')->with('alerts', $alerts);
    }

    /**
     * @param int $conversationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $conversationId)
    {
        try {
            DB::beginTransaction();

            Conversation::where('id', $conversationId)->forceDelete();

            DB::commit();

            $alerts[] = [
                'type' => 'success',
                'message' => 'The conversation was deleted.'
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            $alerts[] = [
                'type' => 'error',
                'message' => 'The conversation was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param Conversation $conversation
     * @return Conversation
     */
    private function prepareConversationObject(Conversation &$conversation)
    {
        $userA = $conversation->userA;
        $userB = $conversation->userB;

        if ($userB->roles[0]->id == 3) {
            $conversation->userA = $userB;
            $conversation->userB = $userA;
        }

        return $conversation;
    }

    /**
     * @param $conversation
     * @return array
     */
    private function getParticipantNotes($conversation)
    {
        $userANotes = ConversationNote::where('user_id', $conversation->userA->id)
            ->where('conversation_id', $conversation->id)
            ->orderBy('category_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $userBNotes = ConversationNote::where('user_id', $conversation->userB->id)
            ->where('conversation_id', $conversation->id)
            ->orderBy('category_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return [$userANotes, $userBNotes];
    }

    /**
     * @param Request $request
     * @return int|mixed
     */
    protected function resolveCurrentPage(Request $request)
    {
        $page = ($request->get('page') === 1 || is_null($request->get('page'))) ? 1 : $request->get('page');
        return $page;
    }
}
