<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Managers\ConversationManager;
use App\OpenConversationPartner;
use App\User;
use Carbon\Carbon;
use Config;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

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

    public function getLockedInformation($conversationId)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::findOrFail($conversationId);

        $response = [
            'locked_by_user_id' => $conversation->getLockedByUserId(),
            'locked_at' => $conversation->getLockedAt()
        ];

        return response()->json($response, 200);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getChatTranslations(int $userId) {
        $chatTranslations = File::getRequire(base_path() . '/resources/lang/' . User::find($userId)->getLocale() . '/chat.php');

        return response()->json($chatTranslations, 200);
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $offset
     * @param int $limit
     * @return JsonResponse
     */
    public function getConversationByParticipantIds(
        int $userAId,
        int $userBId,
        int $offset = 0,
        int $limit = 0
    ) {
        try {
            $conversation = $this->conversationManager
                ->retrieveConversation(
                    $userAId,
                    $userBId,
                    $offset,
                    $limit
                );

            if (!($conversation instanceof Conversation)) {
                return response()->json('No conversation exists between these users', 404);
            }

            return response()->json($conversation);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $messageIdHigherThan
     * @return JsonResponse
     */
    public function getConversationMessagesWithIdHigherThanByParticipantIds(
        int $userAId,
        int $userBId,
        int $messageIdHigherThan
    ) {
        try {
            $messages = $this->conversationManager
                ->getConversationMessagesWithIdHigherThanByParticipantIds(
                    $userAId,
                    $userBId,
                    $messageIdHigherThan
                );

            return response()->json($messages);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getHighestConversationId()
    {
        try {
            $conversationId = $this->conversationManager->getHighestConversationId();

            return response()->json($conversationId);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getConversationsByUserId(int $userId)
    {
        try {
            $conversations = $this->conversationManager->getConversationsByUserId($userId);

            return response()->json($conversations);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $userId
     * @param bool $value
     * @return JsonResponse
     */
    public function setConversationActivityForUserId(int $userAId, int $userBId, int $userId, bool $value)
    {
        try {
            $conversation = $this->conversationManager->retrieveConversation($userAId, $userBId);

            if (!($conversation instanceof Conversation)) {
                return response()->json('No conversation exists between these users', 404);
            }

            if ($conversation->getUserAId() === $userId) {
                $conversation->setNewActivityForUserA($value);
            } else {
                $conversation->setNewActivityForUserB($value);
            }

            $conversation->timestamps = false;
            $conversation->save();
            $conversation->timestamps = true;

            return response()->json($conversation);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @param string $state
     * @return JsonResponse
     */
    public function persistConversationManagerState(int $userId, string $state)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            $user->setConversationManagerState($state);
            $user->save();

            return response()->json($state, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getConversationManagerState(int $userId)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            if (!($user instanceof User)) {
                \Log::debug('User with ID: ' . $userId . ' does not exist.');
            }

            return response()->json($user->getConversationManagerState(), 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @param int $partnerId
     * @param bool $state
     * @return JsonResponse
     */
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

            return response()->json($user->openConversationPartners()->allRelatedIds(), 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getOpenConversationPartners(int $userId)
    {
        try {
            $partners = OpenConversationPartner::where('user_id', $userId)
                ->where('created_at', '<=', Carbon::now())
                ->orderBy('created_at', 'asc')
                ->get();

            $return = [];

            /** @var User $partner */
            foreach ($partners as $partner) {
                array_push($return, $partner->partner_id . ':' . $partner->state);
            }

            return response()->json($return);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getOperatorPlatformData()
    {
        $timeStart = Carbon::now();

        \Log::info('received', [$timeStart->toString()]);
        try {
            $return = [
                'newConversations' => $this->conversationManager->getConversationsByCycleStage(
                    [Conversation::CYCLE_STAGE_NEW],
                    1,
                    false
                ),
                'unrepliedConversations' => $this->conversationManager->getConversationsByCycleStage(
                    [Conversation::CYCLE_STAGE_UNREPLIED],
                    1,
                    false
                ),
                'stoppedConversations' => $this->conversationManager->getConversationsByCycleStage(
                    [Conversation::CYCLE_STAGE_STOPPED],
                    1,
                    false
                ),
            ];

            $timeEnd = Carbon::now();

            \Log::info('returned', [$timeEnd->diff($timeStart)]);

            return response()->json($return);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $userId
     * @param int $partnerId
     * @return JsonResponse
     */
    public function removeConversationPartnerId(int $userId, int $partnerId)
    {
        try {
            /** @var User $user */
            $user = User::find($userId);

            $user->removeOpenConversationPartner($partnerId);

            return response()->json($user->openConversationPartners()->allRelatedIds(), 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * @param int $conversationId
     * @return JsonResponse
     */
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

            return response()->json(Conversation::destroy($conversationId), 200);
        } catch (\Exception $exception) {
            \Log::debug($exception->getMessage());
            return response()->json($exception->getMessage(), 500);
        }
    }
}
