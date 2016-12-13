<?php

namespace App\Http\Controllers\Operators;

use App\Conversation;
use App\Flirt;
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
        $newConversationsIds = \DB::table('conversation_messages')
                            ->select('conversation_id')
                            ->groupBy('conversation_id')
                            ->havingRaw('count(distinct sender_id) < 2')
                            ->get();

        $newConversationsIdsArray = $this->getConversationsOrArrayOfIds($newConversationsIds, 1);

        //Select the ids of the new messages (excluding new conversations)
        $conversationsWithNewMessagesIds = \DB::table('conversation_messages')
                        ->join('role_user', function ($join) {
                            $join->on('conversation_messages.sender_id', '=', 'role_user.user_id')
                                 ->where('role_user.role_id', '=', 2);
                        })
                        ->select('conversation_id')
                        ->whereNotIn('conversation_id', $newConversationsIdsArray)
                        ->orderBy('conversation_messages.created_at', 'desc')
                        ->take(1)
                        ->get();

        //Select all unseen flirts
        $newFlirts = Flirt::with(['sender', 'recipient'])->where('seen', 0)->get();

        $newConversations = $this->getConversationsOrArrayOfIds($newConversationsIds);

        $conversationsWithNewMessages = $this->getConversationsOrArrayOfIds($conversationsWithNewMessagesIds);

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
    public function getConversationsOrArrayOfIds($Ids, int $getArrayOfIds = 0)
    {
        if ($Ids->count()) {
            foreach ($Ids as $id) {
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
