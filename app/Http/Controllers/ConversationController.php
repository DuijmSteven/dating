<?php

namespace App\Http\Controllers;

use App\Http\Requests\Backend\Conversations\MessageCreateRequest;
use App\Managers\ConversationManager;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    private $conversationManager;

    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
        parent::__construct();
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

        return redirect()->back()->with('alerts', $alerts);
    }
}
