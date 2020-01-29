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
use App\User;
use App\UserImage;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
        $page = $this->resolveCurrentPage($request);

        $conversationsTotalCount = $this->conversationManager->countConversations();

        $conversations = $this->conversationManager->getPaginated(
            'any',
            'any',
            20,
            ($page - 1) * 20
        );

        $paginator = new LengthAwarePaginator($conversations, $conversationsTotalCount, 20, $page);
        $paginator->setPath('/backend/conversations');
        return view(
            'admin.conversations.overview',
            [
                'title' => 'Conversations Overview - ' . \config('app.name'),
                'headingLarge' => 'Conversations',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'conversations' => $paginator
            ]
        );
    }

    /**
     * @param int $conversationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::with(['userA', 'userB', 'messages'])->findOrFail($conversationId);
        $conversation = $this->prepareConversationObject($conversation);

        //dd($conversation);

        [$userANotes, $userBNotes] = $this->getParticipantNotes($conversation);

        return view(
            'admin.conversations.show',
            [
                'title' => 'Conversation (id: ' . $conversationId . ') - ' . \config('app.name'),
                'headingLarge' => 'Conversation (ID: ' . $conversationId . ')',
                'headingSmall' => $conversation->userA->username .
                    ' (id: ' . $conversation->userA->id . ') - ' .
                    $conversation->userB->username .
                    ' (id:' . $conversation->userB->id . ')',
                'carbonNow' => Carbon::now(),
                'conversation' => $conversation,
                'userANotes' => $userANotes,
                'userBNotes' => $userBNotes
            ]
        );
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showNew(int $userAId, int $userBId)
    {
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
                'carbonNow' => Carbon::now(),
                'conversation' => $conversation,
                'userANotes' => collect([]),
                'userBNotes' => collect([])
            ]
        );
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @throws \Exception
     */
    public function checkIfConversationExists(int $userAId, int $userBId) {
        $conversation = Conversation::where('user_a_id', $userAId)
            ->where('user_b_id', $userBId)
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
     */
    public function addInvisibleImageToConversation(AddInvisibleImageToConversationRequest $request)
    {
        /** @var integer $conversationId */
        $conversationId = $request->get('conversation_id');
        $body = $request->get('body') ?? null;

        /** @var UserImage $image */
        $image = UserImage::find($request->get('image_id'));

        /** @var User $sender */
        $sender = User::find($request->get('sender_id'));

        /** @var User $recipient */
        $recipient = User::find($request->get('recipient_id'));

        $message = new ConversationMessage();
        $message->setSenderId($sender->getId());
        $message->setRecipientId($recipient->getId());
        $message->setHasAttachment(true);
        $message->setConversationId($conversationId);
        $message->setType('generic');
        $message->setBody($body);
        $message->save();

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

        $messageAttachmentsPath = StorageHelper::messageAttachmentsPath($conversationId);

        $newFilenameWithoutExtension = md5(microtime() . $filenameWithoutExtension);
        $newFilename = $newFilenameWithoutExtension . '.' . $fileExtension;
        $newThumbFilename = $newFilenameWithoutExtension . '_thumb.' . $fileExtension;
        $newFilepath = $messageAttachmentsPath . $newFilename;
        $newThumbFilepath = $messageAttachmentsPath . $newThumbFilename;

        Storage::disk('cloud')->copy(
            $existingFilepath,
            $newFilepath
        );

        Storage::disk('cloud')->copy(
            $existingThumbFilepath,
            $newThumbFilepath
        );

        $messageAttachment = new MessageAttachment();
        $messageAttachment->setConversationId($conversationId);
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

        if ($recipientHasMessageNotificationsEnabled) {
            $onlineUserIds = Activity::users(1)->pluck('user_id')->toArray();

            if (!in_array($recipient->getId(), $onlineUserIds)) {

                $messageReceivedEmail = (new MessageReceived($sender, $recipient))->onQueue('emails');

                Mail::to($recipient)
                    ->queue($messageReceivedEmail);
            }
        }

        return redirect()->back();
    }

    /**
     * @param MessageCreateRequest $messageCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MessageCreateRequest $messageCreateRequest)
    {
        $messageData = $messageCreateRequest->all();

        try {
            $conversationMessage = $this->conversationManager->createMessage($messageData);

            /** @var User $recipient */
            $recipient = User::find($messageData['recipient_id']);

            /** @var User $sender */
            $sender = User::find($messageData['sender_id']);

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

            if ($recipientHasMessageNotificationsEnabled) {
                $onlineUserIds = Activity::users(1)->pluck('user_id')->toArray();

                if (!in_array($recipient->getId(), $onlineUserIds)) {

                    $messageReceivedEmail = (new MessageReceived($sender, $recipient))->onQueue('emails');

                    Mail::to($recipient)
                        ->queue($messageReceivedEmail);
                }
            }

            $sender = User::find($messageData['sender_id']);

            if ($sender->roles()->get()[0]->name == 'bot') {
                $activity = new Activity;
                $activity->id = bcrypt((int) time() . $messageData['sender_id'] . $messageData['recipient_id']);
                $activity->last_activity = time();
                $activity->user_id = $sender->getId();
                $activity->save();
            }

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was sent successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => $exception->getMessage()
            ];
        }

        return redirect()->route(
            'operator-platform.conversations.show',
            [$conversationMessage->getConversationId()]
        )->with('alerts', $alerts);
    }

    /**
     * @param int $conversationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $conversationId)
    {
        try {
            Conversation::destroy($conversationId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The article was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The article was not deleted due to an exception.'
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
