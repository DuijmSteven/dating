<?php

namespace App\Http\Controllers;

use App\ConversationMessage;
use App\Events\MessageSent;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Managers\ConversationManager;
use App\Http\Controllers\Controller;
use App\User;

class ConversationController extends Controller
{
    private $conversationManager;

    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
        parent::__construct();
    }

    public function index()
    {
        return view('frontend.chat');
    }

    public function store(MessageCreateRequest $messageCreateRequest)
    {
        $messageData = $messageCreateRequest->all();

        try {
            $this->conversationManager->createMessage($messageData);

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

        $conversationMessage = ConversationMessage::where('conversation_id', $messageData['conversation_id'])->first();

        broadcast(new MessageSent($user, $conversationMessage));

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
