<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\ConversationManager;
use App\Managers\StatisticsManager;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    const SALES_TAX = 0.21;

    /** @var StatisticsManager */
    private $statisticsManager;

    public function __construct(
        StatisticsManager $statisticsManager,
        ConversationManager $conversationManager,
        ChartsManager $chartsManager,
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
        $this->statisticsManager = $statisticsManager;
        $this->chartsManager = $chartsManager;
        $this->conversationManager = $conversationManager;
    }

    public function dashboard()
    {
        $onlineIds = $this->userActivityService->getOnlineUserIds(
            $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
        );

        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
        $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');

        $startOfElevenDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(11)->startOfDay()->setTimezone('UTC');

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

        $newLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.new_launch_date'));


        $conversionsAllTimeCount = $this->statisticsManager->affiliateConversionsBetweenCount(
            'any',
            $newLaunchDate,
            $endOfToday
        );

        $conversionsLastTenDaysCount = $this->statisticsManager->affiliateConversionsBetweenCount(
            'any',
            $startOfElevenDaysAgo,
            $endOfYesterday
        );

        $allUsersCount = User::where('created_at', '>=', $newLaunchDate)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })->count();

        $viewData = [
            'messageRateLastHour' => $this->statisticsManager->paidMessagesSentByUserTypeLastHour(),
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
            ],
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
                'averageRevenueLastSevenDays' => $this->statisticsManager->revenueBetween(
                        $startOfSevenDaysAgo,
                        $endOfYesterday
                    ) / 7,
                'averageRevenueLastThirtyDays' => $this->statisticsManager->revenueBetween(
                        $startOfThirtyDaysAgo,
                        $endOfYesterday
                    ) / 30,
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
                'allTimeConversionRate' => $allUsersCount > 0 ? $conversionsAllTimeCount / $allUsersCount * 100 : 0,
                'averageLastTenDays' => $conversionsLastTenDaysCount / 10
            ],
        ];

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Dashboard',
                'headingSmall' => '',
                'salesTax' => self::SALES_TAX,
            ]
        ));
    }
}
