<?php

namespace App\Http\Controllers\Admin;

use App\Creditpack;
use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\StatisticsManager;
use App\User;
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

        $viewData = [
            'messageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfToday,
                    $endOfToday
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfWeek,
                    $endOfWeek
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfMonth,
                    $endOfMonth
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
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
                'paymentsChart' => $this->chartsManager->createPaymentsChart(),
                'paymentsMonthlyChart' => $this->chartsManager->createPaymentsMonthlyChart(),
                'revenueMonthlyChart' => $this->chartsManager->createRevenueMonthlyChart(),
                'deactivationsMonthlyChart' => $this->chartsManager->createDeactivationsMonthlyChart(),
                'netPeasantsAcquiredMonthlyChart' => $this->chartsManager->createNetPeasantsAcquiredMonthlyChart(),
                'rpuChart' => $this->chartsManager->createRpuChart(),
                'revenueWithoutSalesTaxChart' => $this->chartsManager->createRevenueWithoutSalesTaxChart(),
                'revenueWithoutSalesTaxMonthlyChart' => $this->chartsManager->createRevenueWithoutSalesTaxMonthlyChart(),
                'registrationsChart' => $this->chartsManager->createRegistrationsChart(),
                'deactivationsChart' => $this->chartsManager->createDeactivationsChart(),
            ]
        ));
    }
}
