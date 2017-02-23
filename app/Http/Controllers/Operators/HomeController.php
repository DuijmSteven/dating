<?php

namespace App\Http\Controllers\Operators;

use App\Conversation;
use App\Flirt;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Helpers\ccampbell\ChromePhp\ChromePhp;
use App\Managers\ConversationManager;
use Illuminate\Http\Request;

class HomeController extends \App\Http\Controllers\Controller
{
    /** @var ConversationManager */
    private $conversationManager;

    /**
     * @param ConversationManager $conversationManager
     */
    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDashboard()
    {
        return view(
            'operators.dashboard',
            [
                'newConversations' => $this->conversationManager->newPeasantBotConversations(),
                'unrepliedConversations' => $this->conversationManager->unrepliedPeasantBotConversations(),
                //'newFlirtConversations' => $this->conversationManager->newFlirts(),
            ]
        );
    }
}
