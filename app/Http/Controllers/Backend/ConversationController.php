<?php

namespace App\Http\Controllers\Backend;

use App\ConversationNote;
use App\Helpers\ccampbell\ChromePhp\ChromePhp;
use App\Http\Requests\Backend\Conversations\MessageCreateRequest;
use App\Conversation;
use App\Managers\ConversationManager;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    private $conversationManager;

    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
        parent::__construct();
    }

    public function index()
    {
        $conversations = $this->conversationManager->getAll();

        return view(
            'backend.conversations.index',
            [
                'title' => 'Conversations Overview - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Conversations',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'conversations' => $conversations
            ]
        );
    }

    public function show(int $conversationId)
    {
        $conversation = Conversation::with(['userA', 'userB', 'messages'])->find($conversationId);

        if (!($conversation instanceof Conversation)) {
            throw new \Exception;
        }

        $conversation = $this->prepareConversationObject($conversation);

        $userANotes = ConversationNote::where('user_id', $conversation->userA->id)->where('conversation_id', $conversation->id)->get();

        $userBNotes = ConversationNote::where('user_id', $conversation->userB->id)->where('conversation_id', $conversation->id)->get();

        return view(
            'backend.conversations.show',
            [
                'title' => 'Conversation (id: ' . $conversationId . ') - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Conversation (ID: ' . $conversationId . ')',
                'headingSmall' => $conversation->userA->username .
                    ' (id: ' . $conversation->userA->id . ') - ' .
                    $conversation->userB->username .
                    ' (id:' . $conversation->userB->id . ')',
                'carbonNow' => Carbon::now(),
                'conversation' => $conversation,
                'userANotes' => $userANotes,
                'userBNotes' => $userBNotes
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
