<?php

namespace App\Http\Controllers\Admin;

use App\Charts\RegistrationsChart;
use App\Http\Controllers\Controller;
use App\Managers\StatisticsManager;
use App\User;
use Carbon\Carbon;
use Cornford\Googlmapper\Mapper;
use Kim\Activity\Activity;

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
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $onlineBots = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->get();

        $onlinePeasants = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->get();

        $viewData = [
            'numberOfOnlineBots' => count($onlineBots),
            'numberOfOnlinePeasants' => count($onlinePeasants),
            'revenueStatistics' => [
                'revenueToday' => $this->statisticsManager->revenueOnDate(Carbon::today('Europe/Amsterdam')),
                'revenueYesterday' => $this->statisticsManager->revenueOnDate(Carbon::yesterday('Europe/Amsterdam')),
                'revenueCurrentWeek' => $this->statisticsManager->revenueBetween(
                    Carbon::now('Europe/Amsterdam')->startOfWeek(),
                    Carbon::now('Europe/Amsterdam')->endOfWeek()
                ),
                'revenueCurrentMonth' => $this->statisticsManager->revenueBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth(),
                    Carbon::now('Europe/Amsterdam')->endOfMonth()
                ),
                'revenuePreviousMonth' => $this->statisticsManager->revenueBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth(),
                    Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()
                ),
                'revenueCurrentYear' => $this->statisticsManager->revenueBetween(
                    Carbon::now('Europe/Amsterdam')->startOfYear(),
                    Carbon::now('Europe/Amsterdam')->endOfYear()
                )
            ],
            'registrationStatistics' => [
                'registrationsToday' => $this->statisticsManager->registrationsCountOnDay(Carbon::today('Europe/Amsterdam')),
                'registrationsYesterday' => $this->statisticsManager->registrationsCountOnDay(Carbon::yesterday('Europe/Amsterdam')),
                'registrationsCurrentWeek' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfWeek(),
                    Carbon::now('Europe/Amsterdam')->endOfWeek()
                ),
                'registrationsCurrentMonth' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth(),
                    Carbon::now('Europe/Amsterdam')->endOfMonth()
                ),
                'registrationsPreviousMonth' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth(),
                    Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()
                ),
                'registrationsCurrentYear' => $this->statisticsManager->registrationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfYear(),
                    Carbon::now('Europe/Amsterdam')->endOfYear()
                )
            ],
            'messageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentCountOnDay(Carbon::today('Europe/Amsterdam')),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentCountOnDay(Carbon::yesterday('Europe/Amsterdam')),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfWeek(),
                    Carbon::now('Europe/Amsterdam')->endOfWeek()
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth(),
                    Carbon::now('Europe/Amsterdam')->endOfMonth()
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth(),
                    Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfYear(),
                    Carbon::now('Europe/Amsterdam')->endOfYear()
                )
            ],
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'peasant',
                    Carbon::today('Europe/Amsterdam')
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'peasant',
                    Carbon::yesterday('Europe/Amsterdam')
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now('Europe/Amsterdam')->startOfWeek(),
                    Carbon::now('Europe/Amsterdam')->endOfWeek()
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now('Europe/Amsterdam')->startOfMonth(),
                    Carbon::now('Europe/Amsterdam')->endOfMonth()
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth(),
                    Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    Carbon::now('Europe/Amsterdam')->startOfYear(),
                    Carbon::now('Europe/Amsterdam')->endOfYear()
                )
            ],
            'botMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'bot',
                    Carbon::today('Europe/Amsterdam')
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountOnDay(
                    'bot',
                    Carbon::yesterday('Europe/Amsterdam')
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now('Europe/Amsterdam')->startOfWeek(),
                    Carbon::now('Europe/Amsterdam')->endOfWeek()
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now('Europe/Amsterdam')->startOfMonth(),
                    Carbon::now('Europe/Amsterdam')->endOfMonth()
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth(),
                    Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    Carbon::now('Europe/Amsterdam')->startOfYear(),
                    Carbon::now('Europe/Amsterdam')->endOfYear()
                )
            ],
            'peasantDeactivationStatistics' => [
                'deactivationsToday' => $this->statisticsManager->peasantDeactivationsCountOnDay(Carbon::today('Europe/Amsterdam')),
                'deactivationsYesterday' => $this->statisticsManager->peasantDeactivationsCountOnDay(Carbon::yesterday('Europe/Amsterdam')),
                'deactivationsCurrentWeek' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfWeek(),
                    Carbon::now('Europe/Amsterdam')->endOfWeek()
                ),
                'deactivationsCurrentMonth' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth(),
                    Carbon::now('Europe/Amsterdam')->endOfMonth()
                ),
                'deactivationsPreviousMonth' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth(),
                    Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()
                ),
                'deactivationsCurrentYear' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    Carbon::now('Europe/Amsterdam')->startOfYear(),
                    Carbon::now('Europe/Amsterdam')->endOfYear()
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

        $registrationsChart = new RegistrationsChart();

        $query = 'SELECT
                     users.created_at AS registrationDate,
                     COUNT(id) AS registrationCount 
                    FROM users
                    LEFT JOIN role_user on role_user.user_id = users.id
                    WHERE role_user.role_id = ' . User::TYPE_PEASANT . '
                    GROUP BY DAY(registrationDate)
                    ORDER BY registrationDate ASC';

        $results = \DB::select($query);

        $labels = [];
        $counts = [];
        foreach ($results as $result) {
            $labels[] = explode(' ', $result->registrationDate)[0];
            $counts[] = $result->registrationCount;
        }


        $registrationsChart->labels($labels);
        $registrationsChart->dataset('Registrations over time', 'line', $counts);

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \config('app.name'),
                'headingLarge' => 'Dashboard',
                'headingSmall' => 'Site Statistics',
                'registrationsChart' => $registrationsChart
            ]
        ));
    }
}
