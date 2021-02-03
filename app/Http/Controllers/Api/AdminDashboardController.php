<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Managers\ConversationManager;
use App\Managers\StatisticsManager;
use App\Role;
use App\Services\UserActivityService;
use App\User;

class AdminDashboardController
{
    /**
     * @var UserActivityService
     */
    private UserActivityService $userActivityService;
    /**
     * @var ConversationManager
     */
    private ConversationManager $conversationManager;
    /**
     * @var StatisticsManager
     */
    private StatisticsManager $statisticsManager;

    public function __construct(
        UserActivityService $userActivityService,
        ConversationManager $conversationManager,
        StatisticsManager $statisticsManager
    ) {
        $this->userActivityService = $userActivityService;
        $this->conversationManager = $conversationManager;
        $this->statisticsManager = $statisticsManager;
    }

    public function getAdminDashboardData()
    {
        try {
            $onlineIds = $this->userActivityService->getOnlineUserIds(
                $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
            );

            $onlineFemaleStraightBotsCount = $this->userActivityService->getOnlineCountByType(
                $onlineIds,
                Role::ROLE_BOT,
                User::GENDER_FEMALE,
                User::GENDER_MALE
            );

            $onlinePeasantsCount = $this->userActivityService->getOnlineCountByType(
                $onlineIds,
                Role::ROLE_PEASANT,
            );

            $activeFemaleStraightBotsCount = $this->userActivityService->getActiveCountByType(
                true,
                Role::ROLE_BOT,
                User::GENDER_FEMALE,
                User::GENDER_MALE
            );

            $activeMaleStraightBotsCount = $this->userActivityService->getActiveCountByType(
                true,
                Role::ROLE_BOT,
                User::GENDER_MALE,
                User::GENDER_FEMALE
            );

            $inactiveFemaleStraightBotsCount = $this->userActivityService->getActiveCountByType(
                false,
                Role::ROLE_BOT,
                User::GENDER_FEMALE,
                User::GENDER_MALE
            );

            $inactiveMaleStraightBotsCount = $this->userActivityService->getActiveCountByType(
                false,
                Role::ROLE_BOT,
                User::GENDER_MALE,
                User::GENDER_FEMALE
            );



            $data = [
                'onlineIds' => $onlineIds,
                'onlineFemaleStraightBotsCount' => $onlineFemaleStraightBotsCount,
                'onlinePeasantsCount' => $onlinePeasantsCount,
                'activeFemaleStraightBotsCount' => $activeFemaleStraightBotsCount,
                'activeMaleStraightBotsCount' => $activeMaleStraightBotsCount,
                'inactiveFemaleStraightBotsCount' => $inactiveFemaleStraightBotsCount,
                'inactiveMaleStraightBotsCount' => $inactiveMaleStraightBotsCount,
                'availableConversationsCount' => $this->conversationManager->getConversationsByCycleStageCount([
                    Conversation::CYCLE_STAGE_NEW,
                    Conversation::CYCLE_STAGE_UNREPLIED
                ]),
                'stoppedConversationsCount' => $this->conversationManager->getConversationsByCycleStageCount([
                    Conversation::CYCLE_STAGE_STOPPED
                ]),
                'messageRateLastHour' => $this->statisticsManager->paidMessagesSentByUserTypeLastHour(),
                'unspentCreditsOfUsersActiveInLastThirtyDays' => $this->statisticsManager->unspentCredits(30),
                'unspentCreditsOfUsersActiveInLastTenDays' => $this->statisticsManager->unspentCredits(10),
                'unspentCreditsOfUsersActiveInLastThreeDays' => $this->statisticsManager->unspentCredits(3),
            ];

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
}
