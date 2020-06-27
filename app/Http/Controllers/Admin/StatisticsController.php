<?php

namespace App\Http\Controllers\Admin;

use App\Creditpack;
use App\Expense;
use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\StatisticsManager;
use App\Payment;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use Kim\Activity\Activity;

class StatisticsController extends Controller
{
    const SALES_TAX = 0.21;

    /** @var StatisticsManager */
    private $statisticsManager;

    /**
     * @var ChartsManager
     */
    private ChartsManager $chartsManager;

    public function __construct(
        StatisticsManager $statisticsManager,
        ChartsManager $chartsManager
    )
    {
        parent::__construct();
        $this->statisticsManager = $statisticsManager;
        $this->chartsManager = $chartsManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
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

        $peasantsWithCreditpack = $this->statisticsManager->peasantsWithCreditpack();

        $allTimePayingUsers = User::whereHas('payments', function ($query) {
            $query->where('status', Payment::STATUS_COMPLETED);
        })->get()->count();

        $allUsers = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })->get()->count();

        $allTimeRevenue = $this->statisticsManager->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday
        );

        $averageRevenuePerAllTimeUser = $allTimeRevenue / $allUsers;
        $averageRevenuePerAllTimePayingUser = $allTimeRevenue / $allTimePayingUsers;

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');
        $googleAdsLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '11-06-2020 00:00:00');

        $xpartnersAdExpensesAllTime = $this->statisticsManager->affiliateExpensesBetween(
            Expense::PAYEE_XPARTNERS,
            Expense::TYPE_ADS,
            $launchDate,
            $endOfToday
        );

        $xpartnersOtherExpensesAllTime = $this->statisticsManager->affiliateExpensesBetween(
            Expense::PAYEE_XPARTNERS,
            Expense::TYPE_OTHER,
            $launchDate,
            $endOfToday
        );

        $xpartnersRevenueAllTime = $this->statisticsManager->affiliateRevenueBetween(
            UserAffiliateTracking::AFFILIATE_XPARTNERS,
            $launchDate,
            $endOfToday
        );

        $xpartnersConversionsAllTimeCount = $this->statisticsManager->affiliateConversionsBetweenCount(
            UserAffiliateTracking::AFFILIATE_XPARTNERS,
            $launchDate,
            $endOfToday
        );

        $xpartnersLeadsAllTimeCount = User::whereHas('affiliateTracking', function ($query) {
            $query->where('affiliate', UserAffiliateTracking::AFFILIATE_XPARTNERS);
        })->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
        ->where('created_at', '>=', $launchDate)
        ->count();

        $viewData = [
            'botMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_BOT,
                    $startOfToday,
                    $endOfToday
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_BOT,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_BOT,
                    $startOfWeek,
                    $endOfWeek
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_BOT,
                    $startOfMonth,
                    $endOfMonth
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_BOT,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    User::TYPE_BOT,
                    $startOfYear,
                    $endOfToday
                )
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
            'xpartnersRevenueStatistics' => [
                'revenueToday' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfToday,
                    $endOfToday
                ),
                'revenueYesterday' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'revenueCurrentWeek' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfWeek,
                    $endOfWeek
                ),
                'revenueCurrentMonth' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfMonth,
                    $endOfMonth
                ),
                'revenuePreviousMonth' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'revenueCurrentYear' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfYear,
                    $endOfToday
                ),
                'allTimeAdExpenses' => $xpartnersAdExpensesAllTime,
                'allTimeOtherExpenses' => $xpartnersOtherExpensesAllTime,
                'allTimeNetRevenue' => $xpartnersRevenueAllTime - $xpartnersAdExpensesAllTime - $xpartnersOtherExpensesAllTime
            ],
            'xpartnersConversionStatistics' => [
                'conversionsToday' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfToday,
                    $endOfToday
                ),
                'conversionsYesterday' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'conversionsCurrentWeek' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfWeek,
                    $endOfWeek
                ),
                'conversionsCurrentMonth' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfMonth,
                    $endOfMonth
                ),
                'conversionsPreviousMonth' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'conversionsCurrentYear' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS,
                    $startOfYear,
                    $endOfToday
                ),
                'allTimeConversionRate' => $xpartnersConversionsAllTimeCount / $xpartnersLeadsAllTimeCount * 100
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
            'topMessagerStatistics' => [
                'this_month' => $this->statisticsManager->topMessagersBetweenDates($startOfMonth, $endOfMonth, 25)
            ],
            'topOperatorMessagerStatistics' => [
                'this_month' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfMonth, $endOfMonth, 25)
            ],
            'userTypeStatistics' => [
                'no_credits' => $this->statisticsManager->peasantsWithNoCreditpackCount(),
                'never_bought' => $this->statisticsManager->peasantsThatNeverHadCreditpackCount(),
                'small' => $this->statisticsManager->filterPeasantsWithCreditpackIdCount(
                    $peasantsWithCreditpack,
                    Creditpack::SMALL
                ),
                'medium' => $this->statisticsManager->filterPeasantsWithCreditpackIdCount(
                    $peasantsWithCreditpack,
                    Creditpack::MEDIUM
                ),
                'large' => $this->statisticsManager->filterPeasantsWithCreditpackIdCount(
                    $peasantsWithCreditpack,
                    Creditpack::LARGE
                ),
                'xl' => $this->statisticsManager->filterPeasantsWithCreditpackIdCount(
                    $peasantsWithCreditpack,
                    Creditpack::XL
                ),
                'all_time_paying_users' => $allTimePayingUsers,
                'averageRevenuePerAllTimePayingUser' => number_format($averageRevenuePerAllTimePayingUser / 100, 2),
                'averageRevenuePerUser' => number_format($averageRevenuePerAllTimeUser / 100, 2)
            ],
        ];

        return view('admin.statistics', array_merge(
            $viewData,
            [
                'title' => 'Statistics - ' . \config('app.name'),
                'headingLarge' => 'Statistics',
                'headingSmall' => '',
                'salesTax' => self::SALES_TAX,
                'registrationsMonthlyChart' => $this->chartsManager->createRegistrationsMonthlyChart(),
                'peasantMessagesMonthlyChart' => $this->chartsManager->createPeasantMessagesMonthlyChart(),
                'paymentsChart' => $this->chartsManager->createPaymentsChart(
                    $googleAdsLaunchDate
                ),
                'paymentsMonthlyChart' => $this->chartsManager->createPaymentsMonthlyChart(),
                'revenueMonthlyChart' => $this->chartsManager->createRevenueMonthlyChart(),
                'deactivationsMonthlyChart' => $this->chartsManager->createDeactivationsMonthlyChart(),
                'netPeasantsAcquiredMonthlyChart' => $this->chartsManager->createNetPeasantsAcquiredMonthlyChart(),
                'rpuChart' => $this->chartsManager->createRpuChart(),
                'revenueWithoutSalesTaxChart' => $this->chartsManager->createRevenueWithoutSalesTaxChart(),
                'revenueWithoutSalesTaxMonthlyChart' => $this->chartsManager->createRevenueWithoutSalesTaxMonthlyChart(),
                'registrationsChart' => $this->chartsManager->createRegistrationsChart(
                    $googleAdsLaunchDate
                ),
                'deactivationsChart' => $this->chartsManager->createDeactivationsChart(
                    $googleAdsLaunchDate
                ),
                'averagePeasantMessagesPerHourChart' => $this->chartsManager->createAveragePeasantMessagesPerHourInPeriodChart(
                    Carbon::now('Europe/Amsterdam')->subDays(10)->setTimezone('UTC'),
                    Carbon::now('Europe/Amsterdam')->setTimezone('UTC')
                ),
                'xpartnersRevenueChart' => $this->chartsManager->createAffiliateRevenueChart(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
            ]
        ));
    }
}
