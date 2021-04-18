<?php

namespace App\Http\Controllers\Admin;

use App\Expense;
use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\StatisticsManager;
use App\Payment;
use App\Role;
use App\Services\UserActivityService;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        ChartsManager $chartsManager,
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
        $this->statisticsManager = $statisticsManager;
        $this->chartsManager = $chartsManager;
    }

    public function mostUseful()
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

        $googleAdsLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '11-06-2020 00:00:00');

        $viewData = [
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
        ];

        return view('admin.statistics.most-useful', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Dashboard',
                'headingSmall' => '',
                'salesTax' => self::SALES_TAX,
                'revenueChart' => $this->chartsManager->createRevenueChart(
                    $googleAdsLaunchDate
                ),
                'netPeasantsAcquiredChart' => $this->chartsManager->createNetPeasantsAcquiredChart(
                    $googleAdsLaunchDate
                ),
            ]
        ));
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

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));
        $googleAdsLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '11-06-2020 00:00:00');

        $viewData = [
//            'botMessageStatistics' => [
//                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                    User::TYPE_BOT,
//                    $startOfToday,
//                    $endOfToday
//                ),
//                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                    User::TYPE_BOT,
//                    $startOfYesterday,
//                    $endOfYesterday
//                ),
//                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                    User::TYPE_BOT,
//                    $startOfWeek,
//                    $endOfWeek
//                ),
//                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                    User::TYPE_BOT,
//                    $startOfMonth,
//                    $endOfMonth
//                ),
//                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                    User::TYPE_BOT,
//                    $startOfPreviousMonthUtc,
//                    $endOfPreviousMonthUtc
//                ),
//                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
//                    User::TYPE_BOT,
//                    $startOfYear,
//                    $endOfToday
//                )
//            ],
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
            'userTypeStatistics' => $this->statisticsManager->getUserTypeStatistics(),
            'excludingXpartnersUserTypeStatistics' => $this->statisticsManager->getexcludingXpartnersLvuStatistics(),
        ];

        return view('admin.statistics.general', array_merge(
            $viewData,
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => '',
                'salesTax' => self::SALES_TAX,
                'averagePeasantMessagesPerHourChart' => $this->chartsManager->createAveragePeasantMessagesPerHourInPeriodChart(
                    Carbon::now('Europe/Amsterdam')->subDays(10)->setTimezone('UTC'),
                    Carbon::now('Europe/Amsterdam')->setTimezone('UTC')
                ),
                'peasantMessagesMonthlyChart' => $this->chartsManager->createPeasantMessagesMonthlyChart(),
                'paymentsChart' => $this->chartsManager->createPaymentsChart(
                    $googleAdsLaunchDate
                ),
                'paymentsMonthlyChart' => $this->chartsManager->createPaymentsMonthlyChart(),
                'revenueMonthlyChart' => $this->chartsManager->createRevenueMonthlyChart(),
                'revenueWithoutSalesTaxChart' => $this->chartsManager->createRevenueWithoutSalesTaxChart(),
                'revenueWithoutSalesTaxMonthlyChart' => $this->chartsManager->createRevenueWithoutSalesTaxMonthlyChart(),
            ]
        ));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function userAcquisition()
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

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));
        $googleAdsLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '11-06-2020 00:00:00');

        $viewData = [
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
        ];

        return view('admin.statistics.user-acquisition', array_merge(
            $viewData,
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => 'User acquisition',
                'salesTax' => self::SALES_TAX,
                'registrationsChart' => $this->chartsManager->createRegistrationsChart(
                    $googleAdsLaunchDate
                ),
                'deactivationsChart' => $this->chartsManager->createDeactivationsChart(
                    $googleAdsLaunchDate
                ),
                'registrationsMonthlyChart' => $this->chartsManager->createRegistrationsMonthlyChart(),
                'deactivationsMonthlyChart' => $this->chartsManager->createDeactivationsMonthlyChart(),
                'netPeasantsAcquiredMonthlyChart' => $this->chartsManager->createNetPeasantsAcquiredMonthlyChart(),
            ]
        ));
    }

    public function operators()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        $startOfPreviousMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfPreviousMonth = $startOfPreviousMonth->copy()->endOfMonth();

        $viewData = [
            'topMessagerStatistics' => [
                'this_month' => $this->statisticsManager->topMessagersBetweenDates($startOfMonth, $endOfMonth, 25)
            ],
            'topOperatorMessagerStatistics' => [
                'today' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfToday, $endOfToday, 50),
                'this_week' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfWeek, $endOfWeek, 50),
                'this_month' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfMonth, $endOfMonth, 25),
            ],
        ];

        return view('admin.statistics.operators', array_merge(
            $viewData,
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => 'Operators',
            ]
        ));
    }

    public function googleAds()
    {
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

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));

        $googleAdsLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '11-06-2020 00:00:00');

        $googleAdsExpensesAllTime = $this->statisticsManager->affiliateExpensesBetween(
            Expense::PAYEE_GOOGLE,
            Expense::TYPE_ADS,
            $launchDate,
            $endOfToday
        );

        $googleAdsRevenueAllTime = $this->statisticsManager->affiliateRevenueBetween(
            UserAffiliateTracking::AFFILIATE_GOOGLE,
            $launchDate,
            $endOfToday
        );

        $googleAdsConversionsAllTimeCount = $this->statisticsManager->affiliateConversionsBetweenCount(
            UserAffiliateTracking::AFFILIATE_GOOGLE,
            $launchDate,
            $endOfToday
        );

        $googleAdsLeadsAllTimeCount = User::whereHas('affiliateTracking', function ($query) {
            $query->where('affiliate', UserAffiliateTracking::AFFILIATE_GOOGLE);
        })->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->where('created_at', '>=', $launchDate)
            ->count();

        $googleAdsConversionsAllTime = $this->statisticsManager->affiliateConversionsBetweenCount(
            UserAffiliateTracking::AFFILIATE_GOOGLE,
            $googleAdsLaunchDate,
            $endOfToday
        );

        $viewData = [
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfToday,
                    $endOfToday,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'messagesSentYesterday' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfYesterday,
                    $endOfYesterday,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfWeek,
                    $endOfWeek,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfMonth,
                    $endOfMonth,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfYear,
                    $endOfToday,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'messagesSentLastYear' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfLastYear,
                    $endOfLastYear,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                )
            ],
            'googleAdsConversionStatistics' => [
                'conversionsToday' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfToday,
                    $endOfToday
                ),
                'conversionsYesterday' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'conversionsCurrentWeek' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfWeek,
                    $endOfWeek
                ),
                'conversionsCurrentMonth' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfMonth,
                    $endOfMonth
                ),
                'conversionsPreviousMonth' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'conversionsCurrentYear' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfYear,
                    $endOfToday
                ),
                'conversionsLastYear' => $this->statisticsManager->affiliateConversionsBetweenCount(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfLastYear,
                    $endOfLastYear
                ),
                'conversionsAllTime' => $googleAdsConversionsAllTime,
                'allTimeConversionRate' => $googleAdsLeadsAllTimeCount ? $googleAdsConversionsAllTimeCount / $googleAdsLeadsAllTimeCount * 100 : '-',
                'allTimeCostPerConversion' => $googleAdsConversionsAllTime ? $googleAdsExpensesAllTime / $googleAdsConversionsAllTime : '-'
            ],
            'googleAdsRevenueStatistics' => [
                'revenueToday' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfToday,
                    $endOfToday
                ),
                'revenueYesterday' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'revenueCurrentWeek' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfWeek,
                    $endOfWeek
                ),
                'revenueCurrentMonth' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfMonth,
                    $endOfMonth
                ),
                'revenuePreviousMonth' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'revenueCurrentYear' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfYear,
                    $endOfToday
                ),
                'revenueLastYear' => $this->statisticsManager->affiliateRevenueBetween(
                    UserAffiliateTracking::AFFILIATE_GOOGLE,
                    $startOfLastYear,
                    $endOfLastYear
                ),
                'averageRevenueLastSevenDays' => $this->statisticsManager->affiliateRevenueBetween(
                        UserAffiliateTracking::AFFILIATE_GOOGLE,
                        $startOfSevenDaysAgo,
                        $endOfYesterday
                    ) / 7,
                'averageRevenueLastThirtyDays' => $this->statisticsManager->affiliateRevenueBetween(
                        UserAffiliateTracking::AFFILIATE_GOOGLE,
                        $startOfThirtyDaysAgo,
                        $endOfYesterday
                    ) / 30,
                'allTimeAdExpenses' => $googleAdsExpensesAllTime,
                'allTimeNetRevenue' => $googleAdsRevenueAllTime - $googleAdsExpensesAllTime,
            ],
            'googleAdsUserTypeStatistics' => $this->statisticsManager->getAffiliateLvuStatistics(UserAffiliateTracking::AFFILIATE_GOOGLE),
        ];

        return view('admin.statistics.google-ads', array_merge(
            $viewData,
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => 'Google Ads',
                'salesTax' => self::SALES_TAX,
                'googleAdsPeasantMessagesChart' => $this->chartsManager->createPeasantMessagesChart(
                    null,
                    $googleAdsLaunchDate,
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'googleLeadsChart' => $this->chartsManager->createGoogleLeadsChart(),
                'googleAdsRevenueChart' => $this->chartsManager->createAffiliateRevenueChart(
                    UserAffiliateTracking::AFFILIATE_GOOGLE
                ),
                'googleAdsConversionsChart' => $this->chartsManager->createAffiliateConversionsChart(UserAffiliateTracking::AFFILIATE_GOOGLE),
            ]
        ));
    }

    public function xpartners()
    {
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

        $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));

        $xpartnersLaunchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-05-2020 00:00:00');

        $xpartnersExpensesAllTime = $this->statisticsManager->affiliateExpensesBetween(
            Expense::PAYEE_XPARTNERS,
            Expense::TYPE_ADS,
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

        $xpartnersConversionsAllTime = $this->statisticsManager->affiliateConversionsBetweenCount(
            UserAffiliateTracking::AFFILIATE_XPARTNERS,
            $xpartnersLaunchDate,
            $endOfToday
        );

        $viewData = [
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfToday,
                    $endOfToday,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'messagesSentYesterday' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfYesterday,
                    $endOfYesterday,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfWeek,
                    $endOfWeek,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfMonth,
                    $endOfMonth,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->paidMessagesSentCount(
                    $startOfYear,
                    $endOfToday,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                )
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
                'conversionsAllTime' => $xpartnersConversionsAllTime,
                'allTimeConversionRate' => $xpartnersConversionsAllTimeCount / $xpartnersLeadsAllTimeCount * 100,
                'allTimeCostPerConversion' => $xpartnersExpensesAllTime / $xpartnersConversionsAllTime
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
                'averageRevenueLastSevenDays' => $this->statisticsManager->affiliateRevenueBetween(
                        UserAffiliateTracking::AFFILIATE_XPARTNERS,
                        $startOfSevenDaysAgo,
                        $endOfYesterday
                    ) / 7,
                'averageRevenueLastThirtyDays' => $this->statisticsManager->affiliateRevenueBetween(
                        UserAffiliateTracking::AFFILIATE_XPARTNERS,
                        $startOfThirtyDaysAgo,
                        $endOfYesterday
                    ) / 30,
                'allTimeAdExpenses' => $xpartnersExpensesAllTime,
                'allTimeNetRevenue' => $xpartnersRevenueAllTime - $xpartnersExpensesAllTime,
            ],
            'xpartnersUserTypeStatistics' => $this->statisticsManager->getAffiliateLvuStatistics(UserAffiliateTracking::AFFILIATE_XPARTNERS),
        ];

        return view('admin.statistics.xpartners', array_merge(
            $viewData,
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => 'Xpartners',
                'salesTax' => self::SALES_TAX,
                'xpartnersPeasantMessagesChart' => $this->chartsManager->createPeasantMessagesChart(
                    null,
                    $xpartnersLaunchDate,
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'xpartnersRevenueChart' => $this->chartsManager->createAffiliateRevenueChart(
                    UserAffiliateTracking::AFFILIATE_XPARTNERS
                ),
                'xpartnersConversionsChart' => $this->chartsManager->createAffiliateConversionsChart(UserAffiliateTracking::AFFILIATE_XPARTNERS),
            ]
        ));
    }

    public function googleAdsKeywords()
    {
        $leadsPerKeyword = DB
            ::table('user_meta')
            ->select('registration_keyword as keyword', DB::raw('count(*) as count'))
            ->where('registration_keyword', '!=', null)
            ->groupBy(['keyword'])
            ->orderBy('count', 'desc')
            ->get();

        $conversionsPerKeyword = DB
            ::table('user_meta')
            ->join('payments', 'user_meta.user_id', '=', 'payments.user_id')
            ->select('registration_keyword as keyword', DB::raw('count(*) as count'))
            ->where('payments.status', '=', Payment::STATUS_COMPLETED)
            ->where('registration_keyword', '!=', null)
            ->groupBy(['keyword'])
            ->orderBy('count', 'desc')
            ->get();

        return view(
            'admin.statistics.google-ads-keywords',
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => 'Google Ads Keywords',
                'leadsPerKeyword' => $leadsPerKeyword,
                'conversionsPerKeyword' => $conversionsPerKeyword,
            ]
        );
    }

    public function bestBots()
    {
        $bestBotsByMessagesReceived = User
            ::whereHas('roles', function ($query) {
                $query->where('id', Role::ROLE_BOT);
            })
            ->withCount('messaged')
            ->orderBy('messaged_count', 'desc')
            ->take(100)
            ->get();

        $bestBotsByOverallViewsReceived = User
            ::whereHas('roles', function ($query) {
                $query->where('id', Role::ROLE_BOT);
            })
            ->withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(100)
            ->get();

        return view(
            'admin.statistics.best-bots',
            [
                'title' => 'Statistics - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Statistics',
                'headingSmall' => 'Best bots',
                'bestBotsByMessagesReceived' => $bestBotsByMessagesReceived,
                'bestBotsByOverallViewsReceived' => $bestBotsByOverallViewsReceived
            ]
        );
    }
}
