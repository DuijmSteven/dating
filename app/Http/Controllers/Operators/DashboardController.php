<?php

namespace App\Http\Controllers\Operators;

use App\Managers\ConversationManager;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Operators
 */
class DashboardController extends \App\Http\Controllers\Controller
{
    /** @var ConversationManager */
    private $conversationManager;

    /**
     * @param ConversationManager $conversationManager
     */
    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
        parent::__construct();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDashboard()
    {
        return view(
            'operators.dashboard',
            [
                'title' => 'Operator dashboard - ' . Auth::user()->username,
                'headingLarge' => 'Operator dashboard',
                'headingSmall' => Auth::user()->username,
                'newConversations' => $this->conversationManager->newPeasantBotConversations(),
                'unrepliedConversations' => $this->conversationManager->unrepliedPeasantBotConversations(),
                'newFlirtConversations' => $this->conversationManager->conversationsWithNewFlirt(),
            ]
        );
    }
}
