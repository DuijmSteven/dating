<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\ConversationManager;
use App\Managers\StatisticsManager;
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
            'availableConversationsCount' => $this->conversationManager->unrepliedPeasantBotConversationsCount() +
                $this->conversationManager->newPeasantBotConversationsCount(),
            'stoppedConversationsCount' => $this->conversationManager->stoppedPeasantBotConversationsCount(),
            'messageRateLastHour' => $this->statisticsManager->messagesSentByUserTypeLastHour(),
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
            'xpartnersRevenueStatistics' => [
                'revenueToday' => $this->statisticsManager->xpartnersRevenueBetween(
                    $startOfToday,
                    $endOfToday
                ),
                'revenueYesterday' => $this->statisticsManager->xpartnersRevenueBetween(
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'revenueCurrentWeek' => $this->statisticsManager->xpartnersRevenueBetween(
                    $startOfWeek,
                    $endOfWeek
                ),
                'revenueCurrentMonth' => $this->statisticsManager->xpartnersRevenueBetween(
                    $startOfMonth,
                    $endOfMonth
                ),
                'revenuePreviousMonth' => $this->statisticsManager->xpartnersRevenueBetween(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'revenueCurrentYear' => $this->statisticsManager->xpartnersRevenueBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'xpartnersConversionStatistics' => [
                'conversionsToday' => $this->statisticsManager->xpartnersConversionsBetweenCount(
                    $startOfToday,
                    $endOfToday
                ),
                'conversionsYesterday' => $this->statisticsManager->xpartnersConversionsBetweenCount(
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'conversionsCurrentWeek' => $this->statisticsManager->xpartnersConversionsBetweenCount(
                    $startOfWeek,
                    $endOfWeek
                ),
                'conversionsCurrentMonth' => $this->statisticsManager->xpartnersConversionsBetweenCount(
                    $startOfMonth,
                    $endOfMonth
                ),
                'conversionsPreviousMonth' => $this->statisticsManager->xpartnersConversionsBetweenCount(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'conversionsCurrentYear' => $this->statisticsManager->xpartnersConversionsBetweenCount(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->paidMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfToday,
                    $endOfToday
                ),
                'messagesSentYesterday' => $this->statisticsManager->paidMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->paidMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfWeek,
                    $endOfWeek
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->paidMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfMonth,
                    $endOfMonth
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->paidMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->paidMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfYear,
                    $endOfToday
                )
            ],
            'peasantPublicChatMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->publicChatMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfToday,
                    $endOfToday
                ),
                'messagesSentYesterday' => $this->statisticsManager->publicChatMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->publicChatMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfWeek,
                    $endOfWeek
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->publicChatMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfMonth,
                    $endOfMonth
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->publicChatMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->publicChatMessagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
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
                ) / $endOfYesterday->diffInHours($startOfYesterday), 0),
                'currentWeek' => $this->statisticsManager->messagesSentByUserTypePerHourCurrentWeek(),
                'currentMonth' => $this->statisticsManager->messagesSentByUserTypePerHourCurrentMonth(),
                'previousMonth' => number_format($this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_PEASANT,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ) / $endOfPreviousMonthUtc->diffInHours($startOfPreviousMonthUtc), 0),
                'currentYear' => $this->statisticsManager->messagesSentByUserTypePerHourCurrentYear()
            ],
            'registrationStatistics' => [
                'registrationsToday' => $this->statisticsManager->registrationsCountBetween(
                    $startOfToday,
                    $endOfToday
                ),
                'registrationsYesterday' => $this->statisticsManager->registrationsCountBetween(
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'registrationsCurrentWeek' => $this->statisticsManager->registrationsCountBetween(
                    $startOfWeek,
                    $endOfWeek
                ),
                'registrationsCurrentMonth' => $this->statisticsManager->registrationsCountBetween(
                    $startOfMonth,
                    $endOfMonth
                ),
                'registrationsPreviousMonth' => $this->statisticsManager->registrationsCountBetween(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'registrationsCurrentYear' => $this->statisticsManager->registrationsCountBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'peasantDeactivationStatistics' => [
                'deactivationsToday' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfToday,
                    $endOfToday
                ),
                'deactivationsYesterday' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'deactivationsCurrentWeek' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfWeek,
                    $endOfWeek
                ),
                'deactivationsCurrentMonth' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfMonth,
                    $endOfMonth
                ),
                'deactivationsPreviousMonth' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'deactivationsCurrentYear' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'topMessagerStatistics' => [
                'today' => $this->statisticsManager->topMessagersBetweenDates($startOfToday, $endOfToday, 50),
                'this_week' => $this->statisticsManager->topMessagersBetweenDates($startOfWeek, $endOfWeek, 50),
            ],
            'topOperatorMessagerStatistics' => [
                'today' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfToday, $endOfToday, 50),
                'this_week' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfWeek, $endOfWeek, 50),
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
                'peasantMessagesChart' => $this->chartsManager->createPeasantMessagesChart(),
                'revenueChart' => $this->chartsManager->createRevenueChart(),
                'xpartnersRevenueChart' => $this->chartsManager->createAffiliateRevenueChart(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'netPeasantsAcquiredChart' => $this->chartsManager->createNetPeasantsAcquiredChart(),
//                'xpartnersConversionsChart' => $this->chartsManager->createXpartnersConversionsChart(),
            ]
        ));
    }
}
