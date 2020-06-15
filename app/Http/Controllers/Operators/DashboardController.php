<?php

namespace App\Http\Controllers\Operators;

use App\Conversation;
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
                'headingLarge' => 'Dashboard',
                'headingSmall' => Auth::user()->username,
                'newConversations' => $this->conversationManager->getConversationsByCycleStage(
                    Conversation::CYCLE_STAGE_NEW,
                    10,
                    true
                ),
                'unrepliedConversations' => $this->conversationManager->getConversationsByCycleStage(
                    Conversation::CYCLE_STAGE_UNREPLIED,
                    10,
                    true
                ),
                'stoppedConversations' => $this->conversationManager->getConversationsByCycleStage(
                    Conversation::CYCLE_STAGE_STOPPED,
                    10,
                    true
                ),
            ]
        );
    }
}
