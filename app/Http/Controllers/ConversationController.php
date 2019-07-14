<?php

namespace App\Http\Controllers;

use App\ConversationMessage;
use App\Events\MessageSent;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Managers\ConversationManager;
use App\Http\Controllers\Controller;
use App\OpenConversationPartner;
use App\User;
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
     * ConversationController constructor.
     * @param ConversationManager $conversationManager
     */
    public function __construct(ConversationManager $conversationManager, ConversationMessage $conversationMessage)
    {
        $this->conversationManager = $conversationManager;
        $this->conversationMessage = $conversationMessage;
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
        try {
            DB::beginTransaction();
            $messageData = $messageCreateRequest->all();

            $conversationMessage = $this->conversationManager->createMessage($messageData);

            $senderId = $messageData['sender_id'];
            $recipientId = $messageData['recipient_id'];

            $recipientPartnerIds = OpenConversationPartner::where('user_id', $recipientId)
                ->get()
                ->pluck('partner_id')
                ->toArray();

            if (!in_array($senderId, $recipientPartnerIds)) {
                /** @var User $recipient */
                $recipient = User::find($recipientId);

                /** @var User $sender */
                $sender = User::find($senderId);

                $recipient->addOpenConversationPartner($sender, 1);
            }

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was sent successfully'
            ];

            \Log::info('end');

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
