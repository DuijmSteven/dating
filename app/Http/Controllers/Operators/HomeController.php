<?php

namespace App\Http\Controllers\Operators;

use App\Conversation;
use Illuminate\Http\Request;

class HomeController extends \App\Http\Controllers\Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDashboard()
    {
        $newConversationsIds = \DB::table('conversation_messages')
                            ->select('conversation_id')
                            ->groupBy('conversation_id')
                            ->havingRaw('count(distinct sender_id) < 2')
                            ->get();

        $newConversations = $this->getNewConversations($newConversationsIds);

        return view(
            'operators.dashboard',
            [
                'newConversations' => $newConversations
            ]
        );
    }

    public function getNewConversations($newConversationsIds)
    {
        foreach ($newConversationsIds as $id) {
            $allIds[] = $id->conversation_id;
        }

        return Conversation::with(['messages', 'userA', 'userB'])->find($allIds);
    }
}
