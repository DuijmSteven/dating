<?php

namespace App\Http\Controllers\Api;

use App\Role;
use App\Services\UserActivityService;
use App\User;

class AdminDashboardController
{
    /**
     * @var UserActivityService
     */
    private UserActivityService $userActivityService;

    public function __construct(
        UserActivityService $userActivityService
    ) {
        $this->userActivityService = $userActivityService;
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
                Role::ROLE_BOT,
                User::GENDER_FEMALE,
                User::GENDER_MALE
            );

            $activeMaleStraightBotsCount = $this->userActivityService->getActiveCountByType(
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
            ];

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
}
