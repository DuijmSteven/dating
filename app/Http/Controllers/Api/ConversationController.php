<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Managers\ConversationManager;
use Illuminate\Http\JsonResponse;

/**
 * Class ConversationController
 * @package App\Http\Controllers
 */
class ConversationController
{
    private $conversationManager;

    /**
     * ConversationController constructor.
     * @param ConversationManager $conversationManager
     */
    public function __construct(ConversationManager $conversationManager)
    {
        $this->conversationManager = $conversationManager;
    }

    public function getConversationByParticipantIds(int $userAId, int $userBId)
    {
        try {
            $conversation = $this->conversationManager->retrieveConversation($userAId, $userBId);

            if (!($conversation instanceof Conversation)) {
                return JsonResponse::create('No conversation exists between these users', 404);
            }

            return JsonResponse::create($conversation);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }
}
