<?php

namespace App\Http\Controllers\Backend;

use App\Conversation;
use App\ConversationNote;
use App\Http\Controllers\Controller;
use App\Managers\ConversationManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ConversationController extends Controller
{
    private $conversationManager;

    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
        parent::__construct();
    }

    public function index(Request $request)
    {
        $page = $this->resolveCurrentPage($request);

        $conversationsTotalCount = $this->conversationManager->countConversations();

        $conversations = $this->conversationManager->getPaginated('any', 'any', 20, ($page - 1) * 20);

        $paginator = new LengthAwarePaginator($conversations, $conversationsTotalCount, 20, $page);
        $paginator->setPath('/backend/conversations');
        return view(
            'backend.conversations.index',
            [
                'title' => 'Conversations Overview - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Conversations',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'conversations' => $paginator
            ]
        );
    }

    /**
     * @param int $conversationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::with(['userA', 'userB', 'messages'])->findOrFail($conversationId);
        $conversation = $this->prepareConversationObject($conversation);

        list($userANotes, $userBNotes) = $this->getParticipantNotes($conversation);

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

    public function destroy(int $conversationId)
    {
        try {
            Conversation::destroy($conversationId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The article was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The article was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
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

    /**
     * @param $conversation
     * @return array
     */
    private function getParticipantNotes($conversation)
    {
        $userANotes = ConversationNote::where('user_id', $conversation->userA->id)
            ->where('conversation_id', $conversation->id)
            ->orderBy('category', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $userBNotes = ConversationNote::where('user_id', $conversation->userB->id)
            ->where('conversation_id', $conversation->id)
            ->orderBy('category', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return array($userANotes, $userBNotes);
    }

    /**
     * @param Request $request
     * @return int|mixed
     */
    protected function resolveCurrentPage(Request $request)
    {
        $page = ($request->get('page') === 1 || is_null($request->get('page'))) ? 1 : $request->get('page');
        return $page;
    }
}
