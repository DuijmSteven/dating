<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Managers\ConversationManager;
use App\Managers\StatisticsManager;
use App\Role;
use App\Services\UserActivityService;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;

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
            $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
            $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
            $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
            $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');

//            $startOfSevenDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(7)->startOfDay()->setTimezone('UTC');
//            $startOfThirtyDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(30)->startOfDay()->setTimezone('UTC');
//
//            $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
//            $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');
//            $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
//            $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

            $startOfPreviousMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
            $endOfPreviousMonth = $startOfPreviousMonth->copy()->endOfMonth();

//            $startOfPreviousMonthUtc = $startOfPreviousMonth->setTimezone('UTC');
//            $endOfPreviousMonthUtc = $endOfPreviousMonth->setTimezone('UTC');
//
//            $startOfLastYear = Carbon::now('Europe/Amsterdam')->subYears(1)->startOfYear()->setTimezone('UTC');
//            $endOfLastYear = Carbon::now('Europe/Amsterdam')->subYears(1)->endOfYear()->setTimezone('UTC');
//            $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');
//
//            $tenMinutesAgo = Carbon::now('Europe/Amsterdam')->subMinutes(10)->setTimezone('UTC');
//            $oneHourAgo = Carbon::now('Europe/Amsterdam')->subHours(1)->setTimezone('UTC');
//            $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

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
                'revenueStatistics' => [
                    'revenueToday' => $this->statisticsManager->revenueBetween(
                        $startOfToday,
                        $endOfToday
                    ),
                    'revenueYesterday' => $this->statisticsManager->revenueBetween(
                        $startOfYesterday,
                        $endOfYesterday
                    ),
//                    'revenueCurrentMonth' => $this->statisticsManager->revenueBetween(
//                        $startOfMonth,
//                        $endOfMonth
//                    ),
//                    'revenuePreviousMonth' => $this->statisticsManager->revenueBetween(
//                        $startOfPreviousMonthUtc,
//                        $endOfPreviousMonthUtc
//                    ),
//                    'revenueCurrentYear' => $this->statisticsManager->revenueBetween(
//                        $startOfYear,
//                        $endOfToday
//                    ),
//                    'revenueLastYear' => $this->statisticsManager->revenueBetween(
//                        $startOfLastYear,
//                        $endOfLastYear
//                    ),
//                    'averageRevenueLastSevenDays' => $this->statisticsManager->revenueBetween(
//                        $startOfSevenDaysAgo,
//                        $endOfYesterday
//                    ) / 7,
//                    'averageRevenueLastThirtyDays' => $this->statisticsManager->revenueBetween(
//                        $startOfThirtyDaysAgo,
//                        $endOfYesterday
//                    ) / 30,
                ],
                'peasantMessageStatistics' => [
                    'messagesSentToday' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfToday,
                        $endOfToday
                    ),
                    'messagesSentYesterday' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfYesterday,
                        $endOfYesterday
                    ),
//                    'messagesSentCurrentWeek' => $this->statisticsManager->paidMessagesSentCount(
//                        $startOfWeek,
//                        $endOfWeek
//                    ),
//                    'messagesSentCurrentMonth' => $this->statisticsManager->paidMessagesSentCount(
//                        $startOfMonth,
//                        $endOfMonth
//                    ),
//                    'messagesSentPreviousMonth' => $this->statisticsManager->paidMessagesSentCount(
//                        $startOfPreviousMonthUtc,
//                        $endOfPreviousMonthUtc
//                    ),
//                    'messagesSentCurrentYear' => $this->statisticsManager->paidMessagesSentCount(
//                        $startOfYear,
//                        $endOfToday
//                    ),
//                    'messagesSentLastYear' => $this->statisticsManager->paidMessagesSentCount(
//                        $startOfLastYear,
//                        $endOfLastYear
//                    )
                ],
            ];

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    public function getSiteDashboardData()
    {
        try {
            $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
            $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
            $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
            $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');

            $startOfSevenDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(7)->startOfDay()->setTimezone('UTC');
            $startOfThirtyDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(30)->startOfDay()->setTimezone('UTC');

            $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
            $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');
            $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
            $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

            $startOfPreviousMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
            $endOfPreviousMonth = $startOfPreviousMonth->copy()->endOfMonth();

            $startOfPreviousMonthUtc = $startOfPreviousMonth->setTimezone('UTC');
            $endOfPreviousMonthUtc = $endOfPreviousMonth->setTimezone('UTC');

            $startOfLastYear = Carbon::now('Europe/Amsterdam')->subYears(1)->startOfYear()->setTimezone('UTC');
            $endOfLastYear = Carbon::now('Europe/Amsterdam')->subYears(1)->endOfYear()->setTimezone('UTC');
            $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');

            $tenMinutesAgo = Carbon::now('Europe/Amsterdam')->subMinutes(10)->setTimezone('UTC');
            $oneHourAgo = Carbon::now('Europe/Amsterdam')->subHours(1)->setTimezone('UTC');
            $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

            $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));

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

            $conversionsAllTimeCount = $this->statisticsManager->affiliateConversionsBetweenCount(
                'any',
                $launchDate,
                $endOfToday
            );

            $allUsersCount = User::whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })->count();

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
                'revenueStatistics' => [
                    'revenueToday' => $this->statisticsManager->revenueBetween(
                        $startOfToday,
                        $endOfToday
                    ),
                    'revenueYesterday' => $this->statisticsManager->revenueBetween(
                        $startOfYesterday,
                        $endOfYesterday
                    ),
                    'revenueCurrentWeek' => $this->statisticsManager->revenueBetween(
                        $startOfWeek,
                        $endOfWeek
                    ),
                    'revenueCurrentMonth' => $this->statisticsManager->revenueBetween(
                        $startOfMonth,
                        $endOfMonth
                    ),
                    'revenuePreviousMonth' => $this->statisticsManager->revenueBetween(
                        $startOfPreviousMonthUtc,
                        $endOfPreviousMonthUtc
                    ),
                    'revenueCurrentYear' => $this->statisticsManager->revenueBetween(
                        $startOfYear,
                        $endOfToday
                    ),
                    'revenueLastYear' => $this->statisticsManager->revenueBetween(
                        $startOfLastYear,
                        $endOfLastYear
                    ),
                    'averageRevenueLastSevenDays' => $this->statisticsManager->revenueBetween(
                        $startOfSevenDaysAgo,
                        $endOfYesterday
                    ) / 7,
                    'averageRevenueLastThirtyDays' => $this->statisticsManager->revenueBetween(
                        $startOfThirtyDaysAgo,
                        $endOfYesterday
                    ) / 30,
                ],
                'peasantMessageStatistics' => [
                    'messagesSentToday' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfToday,
                        $endOfToday
                    ),
                    'messagesSentYesterday' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfYesterday,
                        $endOfYesterday
                    ),
                    'messagesSentCurrentWeek' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfWeek,
                        $endOfWeek
                    ),
                    'messagesSentCurrentMonth' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfMonth,
                        $endOfMonth
                    ),
                    'messagesSentPreviousMonth' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfPreviousMonthUtc,
                        $endOfPreviousMonthUtc
                    ),
                    'messagesSentCurrentYear' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfYear,
                        $endOfToday
                    ),
                    'messagesSentLastYear' => $this->statisticsManager->paidMessagesSentCount(
                        $startOfLastYear,
                        $endOfLastYear
                    )
                ],
                'conversionStatistics' => [
                    'conversionsToday' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfToday,
                        $endOfToday
                    ),
                    'conversionsYesterday' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfYesterday,
                        $endOfYesterday
                    ),
                    'conversionsCurrentWeek' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfWeek,
                        $endOfWeek
                    ),
                    'conversionsCurrentMonth' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfMonth,
                        $endOfMonth
                    ),
                    'conversionsPreviousMonth' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfPreviousMonthUtc,
                        $endOfPreviousMonthUtc
                    ),
                    'conversionsCurrentYear' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfYear,
                        $endOfToday
                    ),
                    'conversionsLastYear' => $this->statisticsManager->affiliateConversionsBetweenCount(
                        'any',
                        $startOfLastYear,
                        $endOfLastYear
                    ),
                    'allTimeConversionRate' => $allUsersCount > 0 ? $conversionsAllTimeCount / $allUsersCount * 100 : 0
                ],
                'topMessagerStatistics' => [
                    'today' => $this->statisticsManager->topMessagersBetweenDates($startOfToday, $endOfToday, 50),
                    'this_week' => $this->statisticsManager->topMessagersBetweenDates($startOfWeek, $endOfWeek, 50),
                ],
                'messagersOnARollStatistics' => [
                    'last_ten_minutes' => [
                        'peasants' => $this->statisticsManager->peasantMessagersOnARoll($tenMinutesAgo, $now, 50, 1),
                        'countLimit' => 1
                    ],
                    'last_hour' => [
                        'peasants' => $this->statisticsManager->peasantMessagersOnARoll($oneHourAgo, $now, 50, 2),
                        'countLimit' => 2
                    ]
                ],
            ];

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
}
