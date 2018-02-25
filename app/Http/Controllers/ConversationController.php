<?php

namespace App\Http\Controllers;

use App\ConversationMessage;
use App\Events\MessageSent;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Managers\ConversationManager;
use App\Http\Controllers\Controller;
use App\User;

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
        $messageData = $messageCreateRequest->all();

        \Log::info($messageData);

        try {
            $conversationMessage = $this->conversationManager->createMessage($messageData);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was sent successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The message was not sent due to an exception.'
            ];
        }

        $user = User::where('id', $messageData['sender_id'])->first();

        $conversationMessage = $this->conversationMessage
            ->where('sender_id', $messageData['sender_id'])
            ->where(function ($query) use ($messageData) {
                $query->where('sender_id', $messageData['sender_id'])
                    ->where('recipient_id', $messageData['recipient_id']);
            })
            ->orWhere(function ($query) use ($messageData)  {
                $query->where('recipient_id', $messageData['sender_id'])
                    ->where('sender_id', $messageData['recipient_id']);
            })
            ->latest()
            ->first();

        broadcast(new MessageSent($user, $conversationMessage, $conversationMessage->getConversationId()));

        return redirect()->back()->with('alerts', $alerts);
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
