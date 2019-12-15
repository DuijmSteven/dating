<?php

namespace App\Http\Controllers;

use App\ConversationMessage;
use App\Events\MessageSent;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Managers\ConversationManager;
use App\Http\Controllers\Controller;
use App\OpenConversationPartner;
use App\User;
use App\UserAccount;
use DB;
use Redis;

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
        $sender = User::find($senderId);

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
