<?php

namespace App\Http\Controllers\Operators;

use App\Conversation;
use App\Flirt;
use App\RoleUser;
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
        //Select the ids of the new conversations
        $newConversationsIds = \DB::select('SELECT 
                                                conversations.id
                                            FROM
                                                conversations
                                            JOIN
                                                (
                                                    SELECT conversation_messages.conversation_id,
                                                           conversation_messages.sender_id
                                                    FROM conversation_messages
                                                    GROUP BY conversation_messages.conversation_id
                                                    HAVING COUNT(DISTINCT (conversation_messages.sender_id)) = 1
                                                ) 
                                                AS messages ON messages.conversation_id = conversations.id
                                            JOIN role_user ON role_user.user_id = messages.sender_id
                                                           AND role_user.role_id = 2
                                            GROUP BY conversations.id');

        \Log::info($newConversationsIds);

        $newConversationsIdsArray = $this->getConversationsOrArrayOfIds($newConversationsIds, 1);

        //Select the ids of the new messages (excluding new conversations)
        $unrepliedConversationsIds = \DB::table('conversation_messages')
                                ->select('conversation_messages.conversation_id')
                                ->whereIn('conversation_messages.conversation_id', function ($query) {
                                    $query
                                        ->select('role_user.user_id')
                                        ->from(with(new RoleUser)
                                            ->getTable())
                                        ->where('role_user.role_id', 2);
                                })
                                ->groupBy('conversation_messages.conversation_id')
                                ->havingRaw('count(distinct conversation_messages.sender_id) = 1')
                                ->get();

        //Select all unseen flirts
        $newFlirts = Flirt::with(['sender', 'recipient'])->where('seen', 0)->get();

        $newConversations = $this->getConversationsOrArrayOfIds($newConversationsIds);

        $conversationsWithNewMessages = $this->getConversationsOrArrayOfIds($unrepliedConversationsIds);

        return view(
            'operators.dashboard',
            [
                'newConversations' => $newConversations,
                'conversationsWithNewMessages' => $conversationsWithNewMessages,
                'newFlirts' => $newFlirts
            ]
        );
    }

    /**
     * @param $Ids
     * @param int $getArrayOfIds
     * @return array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getConversationsOrArrayOfIds($ids, int $getArrayOfIds = 0)
    {
        if ($ids->count()) {
            foreach ($ids as $id) {
                $allIds[] = $id->conversation_id;
            }
        } else {
            return [];
        }

        if ($getArrayOfIds) {
            return $allIds;
        } else {
            return Conversation::with(['messages', 'userA', 'userB'])->find($allIds);
        }
    }
}
