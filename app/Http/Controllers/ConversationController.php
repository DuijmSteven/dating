<?php

namespace App\Http\Controllers;

use App\ConversationMessage;
use App\EmailType;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Mail\MessageReceived;
use App\Mail\Welcome;
use App\Managers\ConversationManager;
use App\OpenConversationPartner;
use App\User;
use App\UserAccount;
use DB;
use Kim\Activity\Activity;
use Mail;

/**
 * Class ConversationController
 * @package App\Http\Controllers
 */
class ConversationController extends Controller
{
    /** @var ConversationManager $conversationManager */
    private $conversationManager;

    /** @var ConversationMessage $conversationMessage */
    private $conversationMessage;
    /**
     * @var User
     */
    private $user;

    /**
     * ConversationController constructor.
     * @param ConversationManager $conversationManager
     */
    public function __construct(
        ConversationManager $conversationManager,
        ConversationMessage $conversationMessage,
        User $user
    ) {
        $this->conversationManager = $conversationManager;
        $this->conversationMessage = $conversationMessage;
        $this->user = $user;
        parent::__construct();
    }

    public function index()
    {
        return view('frontend.chat');
    }

    /**
     * @param MessageCreateRequest $messageCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MessageCreateRequest $messageCreateRequest)
    {
        $messageData = $messageCreateRequest->all();
        $senderId = $messageData['sender_id'];
        $recipientId = $messageData['recipient_id'];

        /** @var User $recipient */
        $recipient = User::find($recipientId);

        /** @var User $sender */
        $sender = User::with('emailTypes')->find($senderId);

        $senderCredits = $sender->account->credits;

        if ($senderCredits < 1) {
            throw new \Exception('Not enough credits');
        }

        try {
            DB::beginTransaction();

            $conversationMessage = $this->conversationManager->createMessage($messageData);
            $recipientPartnerIds = OpenConversationPartner::where('user_id', $recipientId)
                ->get()
                ->pluck('partner_id')
                ->toArray();

            if (!in_array($senderId, $recipientPartnerIds)) {
                $recipient->addOpenConversationPartner($sender, 1);
            }

            /** @var UserAccount $senderAccount */
            $senderAccount = $sender->account;

            $senderAccount->setCredits($senderCredits - 1);
            $senderAccount->save();

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

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was sent successfully'
            ];

            DB::commit();
        } catch (\Exception $exception) {
            \Log::info(__CLASS__ . ' - ' . $exception->getMessage());

            DB::rollBack();

            $alerts[] = [
                'type' => 'error',
                'message' => 'The message was not sent due to an exception.'
            ];
        }
    }

    public function conversationMessages($conversationId)
    {
        try {
            $messages = ConversationMessage::where('conversation_id', $conversationId)->with('sender')->get();

            return $messages;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
