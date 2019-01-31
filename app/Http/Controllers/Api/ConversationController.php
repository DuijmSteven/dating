<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Managers\ConversationManager;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Redis;

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

    public function getConversationsByUserId(int $userId)
    {
        try {
            $conversations = $this->conversationManager->getConversationsByUserId($userId);

            return JsonResponse::create($conversations);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function persistConversationPartnerId(int $userId, int $partnerId)
    {
        try {
            $key = 'users.conversationPartnerIds.' . $userId;
            Redis::sadd($key, $partnerId);

            return JsonResponse::create(Redis::smembers('users.conversationPartnerIds.' . $userId), 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function getOpenConversationPartners(int $userId)
    {
        try {
            $ids = Redis::smembers('users.conversationPartnerIds.' . $userId);

            return JsonResponse::create($ids, 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function removeConversationPartnerId(int $userId, int $partnerId)
    {
        try {
            $key = 'users.conversationPartnerIds.' . $userId;
            Redis::srem($key, $partnerId);

            return JsonResponse::create(Redis::smembers('users.conversationPartnerIds.' . $userId), 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }
}
