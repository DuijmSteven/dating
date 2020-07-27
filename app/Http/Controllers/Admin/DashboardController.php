<?php

namespace App\Http\Controllers\Admin;

use App\Conversation;
use App\Expense;
use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\ConversationManager;
use App\Managers\StatisticsManager;
use App\Services\ProbabilityService;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use Kim\Activity\Activity;

class DashboardController extends Controller
{
    const SALES_TAX = 0.21;

    /** @var StatisticsManager */
    private $statisticsManager;

    /**
     * @var ChartsManager
     */
    private ChartsManager $chartsManager;

    /**
     * @var ConversationManager
     */
    private ConversationManager $conversationManager;

    public function __construct(
        StatisticsManager $statisticsManager,
        ConversationManager $conversationManager,
        ChartsManager $chartsManager
    ) {
        parent::__construct();
        $this->statisticsManager = $statisticsManager;
        $this->chartsManager = $chartsManager;
        $this->conversationManager = $conversationManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function dashboard()
    {
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $onlineFemaleStraightBotsCount = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_FEMALE);
                $query->where('looking_for_gender', User::GENDER_MALE);
            })
            ->whereIn('id', $onlineIds)
            ->count();

//        $onlineMaleStraightBotsCount = User::with('roles')
//            ->whereHas('roles', function ($query) {
//                $query->where('id', User::TYPE_BOT);
//            })
//            ->whereHas('meta', function ($query) {
//                $query->where('gender', User::GENDER_MALE);
//                $query->where('looking_for_gender', User::GENDER_FEMALE);
//            })
//            ->whereIn('id', $onlineIds)
//            ->count();

        $onlinePeasantsCount = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereIn('id', $onlineIds)
            ->count();

        $activeFemaleStraightBotsCount = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_FEMALE);
                $query->where('looking_for_gender', User::GENDER_MALE);
            })
            ->where('active', true)
            ->count();

//        $activeMaleStraightBotsCount = User::with('roles')
//            ->whereHas('roles', function ($query) {
//                $query->where('id', User::TYPE_BOT);
//            })
//            ->whereHas('meta', function ($query) {
//                $query->where('gender', User::GENDER_MALE);
//                $query->where('looking_for_gender', User::GENDER_FEMALE);
//            })
//            ->where('active', true)
//            ->count();

        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
        $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');

        $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        $startOfPreviousMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfPreviousMonth = $startOfPreviousMonth->copy()->endOfMonth();

        $startOfPreviousMonthUtc = $startOfPreviousMonth->setTimezone('UTC');
        $endOfPreviousMonthUtc = $endOfPreviousMonth->setTimezone('UTC');

        $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');

        $tenMinutesAgo = Carbon::now('Europe/Amsterdam')->subMinutes(10)->setTimezone('UTC');
        $oneHourAgo = Carbon::now('Europe/Amsterdam')->subHours(1)->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        $googleAdsLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '11-06-2020 00:00:00');

        $conversionsAllTimeCount = $this->statisticsManager->affiliateConversionsBetweenCount(
            'any',
            $launchDate,
            $endOfToday
        );

        $allUsersCount = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })->count();

        $viewData = [
            'onlineFemaleStraightBotsCount' => $onlineFemaleStraightBotsCount,
            //'onlineMaleStraightBotsCount' => $onlineMaleStraightBotsCount,
            'onlinePeasantsCount' => $onlinePeasantsCount,
            'activeFemaleStraightBotsCount' => $activeFemaleStraightBotsCount,
            //'activeMaleStraightBotsCount' => $activeMaleStraightBotsCount,
//            'peasantMessagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                User::TYPE_PEASANT,
//                $startOfToday,
//                $endOfToday
//            ),
            'availableConversationsCount' => $this->conversationManager->getConversationsByCycleStageCount([
                Conversation::CYCLE_STAGE_NEW,
                Conversation::CYCLE_STAGE_UNREPLIED
            ]),
            'stoppedConversationsCount' => $this->conversationManager->getConversationsByCycleStageCount([
                Conversation::CYCLE_STAGE_STOPPED
            ]),
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
                'messagesSentCurrentYear' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'peasantMessagesPerHourStatistics' => [
                'today' => $this->statisticsManager->messagesSentByUserTypePerHourToday(),
                'yesterday' => number_format($this->statisticsManager->messagesSentByUserTypeCountBetween(
                        User::TYPE_PEASANT,
                        $startOfYesterday,
                        $endOfYesterday
                    ) / 24, 0),
                'currentWeek' => $this->statisticsManager->messagesSentByUserTypePerHourCurrentWeek(),
                'currentMonth' => $this->statisticsManager->messagesSentByUserTypePerHourCurrentMonth(),
                'previousMonth' => number_format($this->statisticsManager->messagesSentByUserTypeCountBetween(
                        User::TYPE_PEASANT,
                        $startOfPreviousMonthUtc,
                        $endOfPreviousMonthUtc
                    ) / $endOfPreviousMonthUtc->diffInHours($startOfPreviousMonthUtc), 0),
                'currentYear' => $this->statisticsManager->messagesSentByUserTypePerHourCurrentYear()
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
                'revenueCurrentYear' => $this->statisticsManager->revenueBetween(
                    $startOfYear,
                    $endOfToday
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
                'allTimeConversionRate' => $conversionsAllTimeCount / $allUsersCount * 100
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

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \config('app.name'),
                'headingLarge' => 'Dashboard',
                'headingSmall' => '',
                'salesTax' => self::SALES_TAX,
                'peasantMessagesChart' => $this->chartsManager->createPeasantMessagesChart(
                    null,
                    $googleAdsLaunchDate
                ),
                'revenueChart' => $this->chartsManager->createRevenueChart(
                    $googleAdsLaunchDate
                ),
            ]
        ));
    }
}
