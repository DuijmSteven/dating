<?php

namespace App\Http\Controllers\Admin;

use App\Charts\PaymentsChart;
use App\Charts\PeasantMessagesChart;
use App\Charts\RegistrationsChart;
use App\Charts\RevenueChart;
use App\Http\Controllers\Controller;
use App\Managers\StatisticsManager;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Cornford\Googlmapper\Mapper;
use DateInterval;
use DatePeriod;
use DateTime;
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
     * @throws \Exception
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


        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
        $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');

        $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');
        $startOfPreviousMonth = Carbon::now('Europe/Amsterdam')->subMonth()->startOfMonth()->setTimezone('UTC');
        $endOfPreviousMonth = Carbon::now('Europe/Amsterdam')->subMonth()->endOfMonth()->setTimezone('UTC');
        $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');
        $viewData = [
            'numberOfOnlineBots' => count($onlineBots),
            'numberOfOnlinePeasants' => count($onlinePeasants),
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
                    $startOfPreviousMonth,
                    $endOfPreviousMonth
                ),
                'revenueCurrentYear' => $this->statisticsManager->revenueBetween(
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
                    $startOfPreviousMonth,
                    $endOfPreviousMonth
                ),
                'registrationsCurrentYear' => $this->statisticsManager->registrationsCountBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
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
                    $startOfPreviousMonth,
                    $endOfPreviousMonth
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentCountBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    $startOfToday,
                    $endOfToday
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    $startOfWeek,
                    $endOfWeek
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    $startOfMonth,
                    $endOfMonth
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    $startOfPreviousMonth,
                    $endOfPreviousMonth
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'peasant',
                    $startOfYear,
                    $endOfToday
                )
            ],
            'botMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    $startOfToday,
                    $endOfToday
                ),
                'messagesSentYesterday' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    $startOfYesterday,
                    $endOfYesterday
                ),
                'messagesSentCurrentWeek' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    $startOfWeek,
                    $endOfWeek
                ),
                'messagesSentCurrentMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    $startOfMonth,
                    $endOfMonth
                ),
                'messagesSentPreviousMonth' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
                    $startOfPreviousMonth,
                    $endOfPreviousMonth
                ),
                'messagesSentCurrentYear' => $this->statisticsManager->messagesSentByUserTypeCountBetween(
                    'bot',
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
                    $startOfPreviousMonth,
                    $endOfPreviousMonth
                ),
                'deactivationsCurrentYear' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfYear,
                    $endOfToday
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
                'registrationsChart' => $this->createRegistrationsChart(),
                'peasantMessagesChart' => $this->createPeasantMessagesChart(),
                'paymentsChart' => $this->createPaymentsChart(),
                'revenueChart' => $this->createRevenueChart()
            ]
        ));
    }

    /**
     * @return RevenueChart
     * @throws \Exception
     */
    protected function createRevenueChart(): RevenueChart
    {
        $reveueChart = new RevenueChart();

        $query = 'SELECT
                     payments.created_at AS creationDate,
                     SUM(payments.amount) AS revenueOnDay
                    FROM payments
                    LEFT JOIN users on users.id = payments.user_id
                    LEFT JOIN role_user on role_user.user_id = users.id
                    WHERE role_user.role_id = ' . User::TYPE_PEASANT . '
                     AND payments.status = ' . Payment::STATUS_COMPLETED . '
                    GROUP BY DAY(creationDate)
                    ORDER BY creationDate ASC';

        $results = \DB::select($query);

        $labels = [];
        $counts = [];

        $datesWithRevenue = [];
        $revenuePerDate = [];
        foreach ($results as $result) {
            $datesWithRevenue[] = explode(' ', $result->creationDate)[0];

            $revenuePerDate[explode(' ', $result->creationDate)[0]] = (int) $result->revenueOnDay / 100;
        }

        $period = new DatePeriod(
            new DateTime($datesWithRevenue[0]),
            new DateInterval('P1D'),
            (new DateTime($datesWithRevenue[count($datesWithRevenue) - 1]))->modify('+1 day')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithRevenue)) {
                $counts[] = $revenuePerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $reveueChart->labels($labels);
        $reveueChart->dataset('Revenue over time', 'line', $counts);
        return $reveueChart;
    }

    /**
     * @return PaymentsChart
     * @throws \Exception
     */
    protected function createPaymentsChart(): PaymentsChart
    {
        $paymentsChart = new PaymentsChart();

        $query = 'SELECT
                     payments.created_at AS creationDate,
                     COUNT(payments.id) AS paymentsCount
                    FROM payments
                    LEFT JOIN users on users.id = payments.user_id
                    LEFT JOIN role_user on role_user.user_id = users.id
                    WHERE role_user.role_id = ' . User::TYPE_PEASANT . '
                     AND payments.status = ' . Payment::STATUS_COMPLETED . '
                    GROUP BY DAY(creationDate)
                    ORDER BY creationDate ASC';

        $results = \DB::select($query);

        $labels = [];
        $counts = [];

        $datesWithPayments = [];
        $paymentsPerDate = [];
        foreach ($results as $result) {
            $datesWithPayments[] = explode(' ', $result->creationDate)[0];
            $paymentsPerDate[explode(' ', $result->creationDate)[0]] = $result->paymentsCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithPayments[0]),
            new DateInterval('P1D'),
            (new DateTime($datesWithPayments[count($datesWithPayments) - 1]))->modify('+1 day')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithPayments)) {
                $counts[] = $paymentsPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $paymentsChart->labels($labels);
        $paymentsChart->dataset('Payments over time', 'line', $counts);
        return $paymentsChart;
    }

    /**
     * @return PeasantMessagesChart
     * @throws \Exception
     */
    protected function createPeasantMessagesChart(): PeasantMessagesChart
    {
        $peasantMessagesChart = new PeasantMessagesChart();

        $query = 'SELECT
                     conversation_messages.created_at AS creationDate,
                     COUNT(conversation_messages.id) AS messagesCount
                    FROM conversation_messages
                    LEFT JOIN users on users.id = conversation_messages.sender_id
                    LEFT JOIN role_user on role_user.user_id = users.id
                    WHERE role_user.role_id = ' . User::TYPE_PEASANT . '
                    GROUP BY DAY(creationDate)
                    ORDER BY creationDate ASC';

        $results = \DB::select($query);

        $labels = [];
        $counts = [];

        $datesWithMessages = [];
        $messagesPerDate = [];
        foreach ($results as $result) {
            $datesWithMessages[] = explode(' ', $result->creationDate)[0];
            $messagesPerDate[explode(' ', $result->creationDate)[0]] = $result->messagesCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithMessages[0]),
            new DateInterval('P1D'),
            (new DateTime($datesWithMessages[count($datesWithMessages) - 1]))->modify('+1 day')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithMessages)) {
                $counts[] = $messagesPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $peasantMessagesChart->labels($labels);
        $peasantMessagesChart->dataset('Peasant messages over time', 'line', $counts);
        return $peasantMessagesChart;
    }

    /**
     * @return RegistrationsChart
     * @throws \Exception
     */
    protected function createRegistrationsChart(): RegistrationsChart
    {
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

        $datesWithRegistrations = [];
        $registrationsPerDate = [];
        foreach ($results as $result) {
            $datesWithRegistrations[] = explode(' ', $result->registrationDate)[0];
            $registrationsPerDate[explode(' ', $result->registrationDate)[0]] = $result->registrationCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithRegistrations[0]),
            new DateInterval('P1D'),
            (new DateTime($datesWithRegistrations[count($datesWithRegistrations) - 1]))->modify('+1 day')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithRegistrations)) {
                $counts[] = $registrationsPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $registrationsChart->labels($labels);
        $registrationsChart->dataset('Registrations over time', 'line', $counts);
        return $registrationsChart;
    }
}
