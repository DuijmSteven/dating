<?php

namespace App\Http\Controllers\Operators;

/**
 * Class ConversationController
 * @package App\Http\Controllers\Operators
 */
class ConversationController extends \App\Http\Controllers\Controller
{
    public function showSendMessageAsBot()
    {
        return view(
            'operators.conversations.send-message-as-bot',
            [
                'title' => 'Operator dashboard - Send Message as Bot',
                'headingLarge' => 'Send Message as Bot',
            ]
        );
    }
}
