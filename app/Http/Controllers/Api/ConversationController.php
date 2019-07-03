<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Managers\ConversationManager;
use App\OpenConversationPartner;
use App\User;
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

    public function getHighestConversationId()
    {
        try {
            $conversationId = $this->conversationManager->getHighestConversationId();

            return JsonResponse::create($conversationId);
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

    public function persistConversationManagerState(int $userId, string $state)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            $user->setConversationManagerState($state);

            return JsonResponse::create($state, 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }


    public function getConversationManagerState(int $userId)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            return JsonResponse::create($user->getConversationManagerState(), 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function persistConversationPartnerId(int $userId, int $partnerId, bool $state)
    {
        try {
            $exists = OpenConversationPartner::where('user_id', $userId)
                    ->where('partner_id', $partnerId)
                    ->count() > 0;

            /** @var User $user */
            $user = User::find($userId);

            if ($exists) {
                $user->openConversationPartners()->updateExistingPivot($partnerId, ['state' => $state]);
            } else {
                $partner = User::find($partnerId);
                $user->addOpenConversationPartner($partner, $state);
            }

            return JsonResponse::create($user->openConversationPartners()->allRelatedIds(), 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function getOpenConversationPartners(int $userId)
    {
        try {
            $partners = OpenConversationPartner::where('user_id', $userId)
                ->orderBy('created_at', 'asc')
                ->get();

            $return = [];

            /** @var User $partner */
            foreach ($partners as $partner) {
                array_push($return, $partner->partner_id . ':' . $partner->state);
            }

            return JsonResponse::create($return);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function removeConversationPartnerId(int $userId, int $partnerId)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            $user->removeOpenConversationPartner($partnerId);

            return JsonResponse::create($user->openConversationPartners()->allRelatedIds(), 200);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function deleteConversationById(int $conversationId) {
        try {
            /** @var Conversation $conversation */
            $conversation = Conversation::find($conversationId);

            if (is_null($conversation)) {
                throw new \Exception('The conversation does not exist');
            }

/*            if (\Auth::user()->getId() != $conversation->userA()->getId() && \Auth::user()->getId() != $conversation->userB()->getId()) {
                throw new \Exception('The user attempting to delete the conversation is not a participant of the conversation');
            }*/

            return JsonResponse::create(Conversation::destroy($conversationId), 200);
        } catch (\Exception $exception) {
            \Log::debug($exception->getMessage());
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }
}
