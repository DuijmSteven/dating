<?php

namespace App\Http\Controllers\Admin;

use App\Charts\DeactivationsChart;
use App\Charts\DeactivationsMonthlyChart;
use App\Charts\NetPeasantsAcquiredChart;
use App\Charts\NetPeasantsAcquiredMonthlyChart;
use App\Charts\PaymentsChart;
use App\Charts\PaymentsMonthlyChart;
use App\Charts\PeasantMessagesChart;
use App\Charts\PeasantMessagesMonthlyChart;
use App\Charts\RegistrationsChart;
use App\Charts\RegistrationsMonthlyChart;
use App\Charts\RevenueChart;
use App\Charts\RevenueMonthlyChart;
use App\Creditpack;
use App\Http\Controllers\Controller;
use App\Managers\StatisticsManager;
use App\Payment;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Kim\Activity\Activity;

class DashboardController extends Controller
{
    const BAR_WIDTH = 0.1;

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

        $onlineMaleStraightBotsCount = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            })
            ->whereIn('id', $onlineIds)
            ->count();

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

        $activeMaleStraightBotsCount = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            })
            ->where('active', true)
            ->count();

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
            'onlineFemaleStraightBotsCount' => $onlineFemaleStraightBotsCount,
            'onlineMaleStraightBotsCount' => $onlineMaleStraightBotsCount,
            'onlinePeasantsCount' => $onlinePeasantsCount,
            'activeFemaleStraightBotsCount' => $activeFemaleStraightBotsCount,
            'activeMaleStraightBotsCount' => $activeMaleStraightBotsCount,
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
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
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
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
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
                    $startOfPreviousMonthUtc,
                    $endOfPreviousMonthUtc
                ),
                'deactivationsCurrentYear' => $this->statisticsManager->peasantDeactivationsCountBetween(
                    $startOfYear,
                    $endOfToday
                )
            ],
            'userTypeStatistics' => [
                'no_credits' => $this->statisticsManager->peasantsWithNoCreditpack(),
                'never_bought' => $this->statisticsManager->peasantsThatNeverHadCreditpack(),
                'small' => $this->statisticsManager->filterPeasantsWithCreditpackId(
                    $peasantsWithCreditpack,
                    Creditpack::SMALL
                ),
                'medium' => $this->statisticsManager->filterPeasantsWithCreditpackId(
                    $peasantsWithCreditpack,
                    Creditpack::MEDIUM
                ),
                'large' => $this->statisticsManager->filterPeasantsWithCreditpackId(
                    $peasantsWithCreditpack,
                    Creditpack::LARGE
                ),
            ],
            'topMessagerStatistics' => [
                'today' => $this->statisticsManager->topMessagersBetweenDates($startOfToday, $endOfToday, 25),
                'this_week' => $this->statisticsManager->topMessagersBetweenDates($startOfWeek, $endOfWeek, 25),
                'this_month' => $this->statisticsManager->topMessagersBetweenDates($startOfMonth, $endOfMonth, 25)
            ],
            'topOperatorMessagerStatistics' => [
                'today' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfToday, $endOfToday, 25),
                'this_week' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfWeek, $endOfWeek, 25),
                'this_month' => $this->statisticsManager->topOperatorMessagersBetweenDates($startOfMonth, $endOfMonth, 25)
            ]
        ];

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \config('app.name'),
                'headingLarge' => 'Dashboard',
                'headingSmall' => 'Site Statistics',
                'registrationsChart' => $this->createRegistrationsChart(),
                'registrationsMonthlyChart' => $this->createRegistrationsMonthlyChart(),
                'peasantMessagesChart' => $this->createPeasantMessagesChart(),
                'peasantMessagesMonthlyChart' => $this->createPeasantMessagesMonthlyChart(),
                'paymentsChart' => $this->createPaymentsChart(),
                'paymentsMonthlyChart' => $this->createPaymentsMonthlyChart(),
                'revenueChart' => $this->createRevenueChart(),
                'revenueMonthlyChart' => $this->createRevenueMonthlyChart(),
                'netPeasantsAcquiredChart' => $this->createNetPeasantsAcquiredChart(),
                'deactivationsChart' => $this->createDeactivationsChart(),
                'deactivationsMonthlyChart' => $this->createDeactivationsMonthlyChart(),
                'netPeasantsAcquiredMonthlyChart' => $this->createNetPeasantsAcquiredMonthlyChart(),
            ]
        ));
    }

    /**
     * @return PaymentsChart
     * @throws \Exception
     */
    protected function createPaymentsChart(): PaymentsChart
    {
        $query = \DB::table('payments as p')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, COUNT(p.id) AS paymentsCount'))
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

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
            new DateTime('now')
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

        $paymentsChart = new PaymentsChart();
        $paymentsChart->labels($labels);
        $paymentsChart
            ->dataset('Payments on date', 'line', $counts)
            ->backGroundColor('#3eb3de');

        $paymentsChart
            ->title('Payments per day');

        return $paymentsChart;
    }

    /**
     * @return PaymentsMonthlyChart
     * @throws \Exception
     */
    protected function createPaymentsMonthlyChart(): PaymentsMonthlyChart
    {
        $query = \DB::table('payments as p')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    COUNT(p.id) as paymentsInMonthsCount'
                )
            )
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $monthsWithPayments = [];
        $paymentsPerMonth = [];
        foreach ($results as $result) {
            $monthsWithPayments[] = explode(' ', $result->months)[0];
            $paymentsPerMonth[explode(' ', $result->months)[0]] = $result->paymentsInMonthsCount;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithPayments[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m');

            if (in_array($value->format('Y-m'), $monthsWithPayments)) {
                $counts[] = $paymentsPerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $paymentsMonthlyChart = new PaymentsMonthlyChart();
        $paymentsMonthlyChart->labels($labels);
        $paymentsMonthlyChart
            ->dataset('Payments in month', 'bar', $counts)
            ->backGroundColor('#3eb3de');

        $paymentsMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Payments per month');

        return $paymentsMonthlyChart;
    }

    /**
     * @return PeasantMessagesChart
     * @throws \Exception
     */
    protected function createPeasantMessagesChart(): PeasantMessagesChart
    {
        $query = \DB::table('conversation_messages as cm')
            ->select(\DB::raw('DATE(CONVERT_TZ(cm.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, COUNT(cm.id) AS messagesCount'))
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

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
            new DateTime('now')
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

        $peasantMessagesChart = new PeasantMessagesChart();
        $peasantMessagesChart->labels($labels);
        $peasantMessagesChart
            ->dataset('Peasant messages on date', 'line', $counts)
            ->backGroundColor('#de3e7b');

        $peasantMessagesChart
            ->title('Peasant messages per day');

        return $peasantMessagesChart;
    }

    /**
     * @return PeasantMessagesMonthlyChart
     * @throws \Exception
     */
    protected function createPeasantMessagesMonthlyChart(): PeasantMessagesMonthlyChart
    {
        $query = \DB::table('conversation_messages as cm')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(cm.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    COUNT(cm.id) as peasantMessagesInMonthsCount'
                )
            )
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $monthsWithMessages = [];
        $messagesPerMonth = [];
        foreach ($results as $result) {
            $monthsWithMessages[] = explode(' ', $result->months)[0];
            $messagesPerMonth[explode(' ', $result->months)[0]] = $result->peasantMessagesInMonthsCount;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithMessages[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m');

            if (in_array($value->format('Y-m'), $monthsWithMessages)) {
                $counts[] = $messagesPerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $peasantMessagesMonthlyChart = new PeasantMessagesMonthlyChart();
        $peasantMessagesMonthlyChart->labels($labels);
        $peasantMessagesMonthlyChart
            ->dataset('Peasant messages in month', 'bar', $counts)
            ->backGroundColor('#de3e7b');

        $peasantMessagesMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Peasant messages per month');

        return $peasantMessagesMonthlyChart;
    }

    /**
     * @return RegistrationsChart
     * @throws \Exception
     */
    protected function createRegistrationsChart(): RegistrationsChart
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\')) as registrationDate, COUNT(u.id) AS registrationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->groupBy('registrationDate')
            ->orderBy('registrationDate', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $datesWithRegistrations = [];
        $registrationsPerDate = [];
        foreach ($results as $result) {
            $datesWithRegistrations[] = explode(' ', $result->registrationDate)[0];
            $registrationsPerDate[explode(' ', $result->registrationDate)[0]] = $result->registrationsCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithRegistrations[0]),
            new DateInterval('P1D'),
            new DateTime('now')
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

        $registrationsChart = new RegistrationsChart();
        $registrationsChart->labels($labels);
        $registrationsChart
            ->dataset('Registrations on date', 'line', $counts)
            ->backGroundColor('#deb33e');

        $registrationsChart
            ->title('Registrations per day');

        return $registrationsChart;
    }

    /**
     * @return RegistrationsMonthlyChart
     * @throws \Exception
     */
    protected function createRegistrationsMonthlyChart(): RegistrationsMonthlyChart
    {
        $query = \DB::table('users as u')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    COUNT(u.id) as registrationsInMonthsCount'
                )
            )
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $monthsWithRegistrations = [];
        $registrationsPerMonth = [];
        foreach ($results as $result) {
            $monthsWithRegistrations[] = explode(' ', $result->months)[0];
            $registrationsPerMonth[explode(' ', $result->months)[0]] = $result->registrationsInMonthsCount;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithRegistrations[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m');

            if (in_array($value->format('Y-m'), $monthsWithRegistrations)) {
                $counts[] = $registrationsPerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $registrationsMonthlyChart = new RegistrationsMonthlyChart();
        $registrationsMonthlyChart->labels($labels);
        $registrationsMonthlyChart
            ->dataset('Registrations in month', 'bar', $counts)
            ->backGroundColor('#deb33e');

        $registrationsMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Registrations per month');

        return $registrationsMonthlyChart;
    }

    /**
     * @return RevenueChart
     * @throws \Exception
     */
    protected function createRevenueChart(): RevenueChart
    {
        $query = \DB::table('payments as p')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, SUM(p.amount) as revenueOnDay'))
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $datesWithRevenue = [];
        $revenuePerDate = [];

        foreach ($results as $result) {
            $datesWithRevenue[] = explode(' ', $result->creationDate)[0];
            $revenuePerDate[explode(' ', $result->creationDate)[0]] = (int)$result->revenueOnDay / 100;
        }

        $period = new DatePeriod(
            new DateTime($datesWithRevenue[0]),
            new DateInterval('P1D'),
            new DateTime('now')
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

        $revenueChart = new RevenueChart();
        $revenueChart->labels($labels);

        $revenueChart
            ->dataset('Revenue on date', 'line', $counts)
            ->backGroundColor('#339929');

        $revenueChart
            ->title('Revenue per day');

        return $revenueChart;
    }

    /**
     * @return RevenueMonthlyChart
     * @throws \Exception
     */
    protected function createRevenueMonthlyChart(): RevenueMonthlyChart
    {
        $query = \DB::table('payments as p')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    SUM(p.amount) as revenueInMonth'
                )
            )
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $monthsWithRevenue = [];
        $revenuePerMonth = [];

        foreach ($results as $result) {
            $monthsWithRevenue[] = explode(' ', $result->months)[0];
            $revenuePerMonth[explode(' ', $result->months)[0]] = (int)$result->revenueInMonth / 100;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithRevenue[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m');

            if (in_array($value->format('Y-m'), $monthsWithRevenue)) {
                $counts[] = $revenuePerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $revenueMonthlyChart = new RevenueMonthlyChart();
        $revenueMonthlyChart->labels($labels);
        $revenueMonthlyChart
            ->dataset('Revenue in month', 'bar', $counts)
            ->backGroundColor('#339929');

        $revenueMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Revenue per month');

        return $revenueMonthlyChart;
    }

    /**
     * @return NetPeasantsAcquiredChart
     * @throws \Exception
     */
    protected function createNetPeasantsAcquiredChart(): NetPeasantsAcquiredChart
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\')) as registrationDate, COUNT(u.id) AS registrationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->groupBy('registrationDate')
            ->orderBy('registrationDate', 'ASC');

        $datesAndRegistrationCounts = $query->get();

        $datesWithRegistrations = [];
        $registrationsPerDate = [];
        foreach ($datesAndRegistrationCounts as $dateAndRegistrationsCount) {
            $datesWithRegistrations[] = explode(' ', $dateAndRegistrationsCount->registrationDate)[0];
            $registrationsPerDate[explode(' ', $dateAndRegistrationsCount->registrationDate)[0]] = $dateAndRegistrationsCount->registrationsCount;
        }

        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.deactivated_at, \'UTC\', \'Europe/Amsterdam\')) as deactivationDate, COUNT(u.id) AS deactivationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.deactivated_at', '!=', null)
            ->groupBy('deactivationDate')
            ->orderBy('deactivationDate', 'ASC');

        $datesAndDeactivationCounts = $query->get();

        $labels = [];
        $counts = [];

        $datesWithDeactivations = [];
        $deactivationsPerDate = [];
        foreach ($datesAndDeactivationCounts as $dateAndDeactivationsCount) {
            $datesWithDeactivations[] = explode(' ', $dateAndDeactivationsCount->deactivationDate)[0];
            $deactivationsPerDate[explode(' ', $dateAndDeactivationsCount->deactivationDate)[0]] = $dateAndDeactivationsCount->deactivationsCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithRegistrations[0]),
            new DateInterval('P1D'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithRegistrations)) {
                if (in_array($value->format('Y-m-d'), $datesWithDeactivations)) {
                    $counts[] = $registrationsPerDate[$value->format('Y-m-d')] - $deactivationsPerDate[$value->format('Y-m-d')];
                } else {
                    $counts[] = $registrationsPerDate[$value->format('Y-m-d')];
                }
            } else if (in_array($value->format('Y-m-d'), $datesWithDeactivations)) {
                $counts[] = -$deactivationsPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $netPeasantsAcquiredChart = new NetPeasantsAcquiredChart();
        $netPeasantsAcquiredChart->labels($labels);
        $netPeasantsAcquiredChart
            ->dataset('Net peasants acquired on date', 'line', $counts)
            ->backGroundColor('#58a37f');

        $netPeasantsAcquiredChart
            ->title('Net peasants acquired per day');

        return $netPeasantsAcquiredChart;
    }

    /**
     * @return NetPeasantsAcquiredMonthlyChart
     * @throws \Exception
     */
    protected function createNetPeasantsAcquiredMonthlyChart(): NetPeasantsAcquiredMonthlyChart
    {
        $query = \DB::table('users as u')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    COUNT(u.id) as registrationsCountInMonth'
                )
            )
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $monthsAndRegistrationCounts = $query->get();

        $monthsWithRegistrations = [];
        $registrationsPerMonth = [];
        foreach ($monthsAndRegistrationCounts as $dateAndRegistrationsCount) {
            $monthsWithRegistrations[] = explode(' ', $dateAndRegistrationsCount->months)[0];
            $registrationsPerMonth[explode(' ', $dateAndRegistrationsCount->months)[0]] = $dateAndRegistrationsCount->registrationsCountInMonth;
        }

        $query = \DB::table('users as u')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(u.deactivated_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    COUNT(u.id) as deactivationsCountInMonth'
                )
            )
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.deactivated_at', '!=', null)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $monthsAndDeactivationCounts = $query->get();

        $labels = [];
        $counts = [];

        $monthsWithDeactivations = [];
        $deactivationsPerMonth = [];
        foreach ($monthsAndDeactivationCounts as $dateAndDeactivationsCount) {
            $monthsWithDeactivations[] = explode(' ', $dateAndDeactivationsCount->months)[0];
            $deactivationsPerMonth[explode(' ', $dateAndDeactivationsCount->months)[0]] = $dateAndDeactivationsCount->deactivationsCountInMonth;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithRegistrations[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m');

            if (in_array($value->format('Y-m'), $monthsWithRegistrations)) {
                if (in_array($value->format('Y-m'), $monthsWithDeactivations)) {
                    $counts[] = $registrationsPerMonth[$value->format('Y-m')] - $deactivationsPerMonth[$value->format('Y-m')];
                } else {
                    $counts[] = $registrationsPerMonth[$value->format('Y-m')];
                }
            } else if (in_array($value->format('Y-m'), $monthsWithDeactivations)) {
                $counts[] = -$deactivationsPerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $netPeasantsAcquiredMonthlyChart = new NetPeasantsAcquiredMonthlyChart();
        $netPeasantsAcquiredMonthlyChart->labels($labels);
        $netPeasantsAcquiredMonthlyChart
            ->dataset('Net peasants acquired in month', 'bar', $counts)
            ->backGroundColor('#58a37f');

        $netPeasantsAcquiredMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Net peasants acquired per month');

        return $netPeasantsAcquiredMonthlyChart;
    }

    /**
     * @return DeactivationsChart
     * @throws \Exception
     */
    protected function createDeactivationsChart(): DeactivationsChart
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.deactivated_at, \'UTC\', \'Europe/Amsterdam\')) as deactivationDate, COUNT(u.id) AS deactivationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.deactivated_at', '!=', null)
            ->groupBy('deactivationDate')
            ->orderBy('deactivationDate', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $datesWithDeactivations = [];
        $deactivationsPerDate = [];
        foreach ($results as $result) {
            $datesWithDeactivations[] = explode(' ', $result->deactivationDate)[0];
            $deactivationsPerDate[explode(' ', $result->deactivationDate)[0]] = $result->deactivationsCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithDeactivations[0]),
            new DateInterval('P1D'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithDeactivations)) {
                $counts[] = $deactivationsPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $deactivationsChart = new DeactivationsChart();
        $deactivationsChart->labels($labels);
        $deactivationsChart
            ->dataset('Deactivations on date', 'line', $counts)
            ->backGroundColor('#a37158');

        $deactivationsChart
            ->title('Deactivations per day');

        return $deactivationsChart;
    }

    /**
     * @return DeactivationsChart
     * @throws \Exception
     */
    protected function createDeactivationsMonthlyChart(): DeactivationsMonthlyChart
    {
        $query = \DB::table('users as u')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(u.deactivated_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, 
                    COUNT(u.id) as deactivationsCountInMonth'
                )
            )
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.deactivated_at', '!=', null)
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        $labels = [];
        $counts = [];

        $monthsWithDeactivations = [];
        $deactivationsPerMonth = [];
        foreach ($results as $result) {
            $monthsWithDeactivations[] = explode(' ', $result->months)[0];
            $deactivationsPerMonth[explode(' ', $result->months)[0]] = $result->deactivationsCountInMonth;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithDeactivations[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m');

            if (in_array($value->format('Y-m'), $monthsWithDeactivations)) {
                $counts[] = $deactivationsPerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $deactivationsMonthlyChart = new DeactivationsMonthlyChart();
        $deactivationsMonthlyChart->labels($labels);
        $deactivationsMonthlyChart
            ->dataset('Deactivations in month', 'bar', $counts)
            ->backGroundColor('#a37158');

        $deactivationsMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Deactivations per month');

        return $deactivationsMonthlyChart;
    }
}
