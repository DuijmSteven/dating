<?php

namespace App\Http\Controllers\Backend;

use App\Conversation;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    public function show(int $conversationId)
    {
        $conversation = Conversation::with(['userA', 'userB', 'messages'])->find($conversationId);

        $conversation = $this->prepareConversationObject($conversation);

        return view(
            'backend.conversations.show',
            [
                'title' => 'Conversation (id: ' . $conversationId . ') - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Conversation',
                'headingSmall' => $conversation->userA->username .
                    ' (id: ' . $conversation->userA->id . ') - ' .
                    $conversation->userB->username .
                    ' (id:' . $conversation->userB->id . ')',
                'carbonNow' => Carbon::now(),
                'conversation' => $conversation
            ]
        );
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
}
