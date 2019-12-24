<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\StatisticsManager;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /** @var StatisticsManager */
    private $statisticsManager;

    public function __construct(StatisticsManager $statisticsManager)
    {
        parent::__construct();
        $this->statisticsManager = $statisticsManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $viewData = [
            'registrationStatistics' => [
                'registrationsToday' => $this->statisticsManager->registrationsCountOnDay(Carbon::today()),
                'registrationsYesterday' => $this->statisticsManager->registrationsCountOnDay(Carbon::yesterday()),
                'registrationsCurrentWeek' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ),
                'registrationsCurrentMonth' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ),
                'registrationsPreviousMonth' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ),
                'registrationsCurrentYear' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                )
            ],
            'messageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentCountOnDay(Carbon::today()),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentCountOnDay(Carbon::yesterday()),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                )
            ],
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'peasant',
                    Carbon::today()
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'peasant',
                    Carbon::yesterday()
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                )
            ],
            'botMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'bot',
                    Carbon::today()
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'bot',
                    Carbon::yesterday()
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                )
            ],
            'peasantDeactivationStatistics' => [
                'deactivationsToday' => $this->statisticsManager->peasantDeactivationsCountOnDay(Carbon::today()),
                'deactivationsYesterday' => $this->statisticsManager->peasantDeactivationsCountOnDay(Carbon::yesterday()),
                'deactivationsCurrentWeek' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ),
                'deactivationsCurrentMonth' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ),
                'deactivationsPreviousMonth' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ),
                'deactivationsCurrentYear' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                )
            ],
            'userTypeStatistics' => [
                'no_credits' => $this->statisticsManager->peasantsWithNoCreditpack(),
                'never_bought' => $this->statisticsManager->peasantsThatNeverHadCreditpack(),
                'small' => $this->statisticsManager->peasantsWithSmallCreditpack(),
                'medium' => $this->statisticsManager->peasantsWithMediumCreditpack(),
                'large' => $this->statisticsManager->peasantsWithLargeCreditpack(),
            ],
        ];

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \config('app.name'),
                'headingLarge' => 'Dashboard',
                'headingSmall' => 'Site Statistics',
            ]
        ));
    }
}
