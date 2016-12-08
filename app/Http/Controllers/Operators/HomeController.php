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
        Conversation::with(['messages', 'userA', 'userB'])->whereHas('userA', function($query) {
            $query->where('');
        });

        return view(
            'operators.dashboard',
            [
                'newConversations' => Conversation::with('messages')->get()
            ]
        );
    }
}
