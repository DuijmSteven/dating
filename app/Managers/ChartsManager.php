<?php

namespace App\Managers;

use App\Charts\AveragePeasantMessagesPerHourChart;
use App\Charts\ConversionsChart;
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
use App\Payment;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;

class ChartsManager
{
    const BAR_WIDTH = 0.3;
    const SALES_TAX = 0.21;

    /**
     *
     * @return PeasantMessagesChart|null
     * @throws \Exception
     */
    public function createPeasantMessagesChart(int $userId = null, $since = null, $affiliate = null)
    {
        $query = \DB::table('conversation_messages as cm')
            ->select(\DB::raw('DATE(CONVERT_TZ(cm.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, COUNT(cm.id) AS messagesCount'))
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('cm.created_at', '>=', $since)
            ->where('cm.paid', true);

        if ($affiliate) {
            $query->leftJoin('user_affiliate_tracking as uat', 'u.id', 'uat.user_id')
                ->where('uat.affiliate', $affiliate);
        }

        if (null == $userId) {
            $query->where('ru.role_id', User::TYPE_PEASANT);
        } else {
            $query->where('u.id', $userId);
        }

        $query->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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

        $chartTitle = '';

        if ($affiliate) {
            $chartTitle .= ucfirst($affiliate) . ' ';
        }

        $chartTitle .= 'Peasant Messages';

        $peasantMessagesChart
            ->dataset('' . $chartTitle . ' on date', 'line', $counts)
            ->backGroundColor('#de3e7b');

        $peasantMessagesChart
            ->title($chartTitle . ' per day');

        return $peasantMessagesChart;
    }

    /**
     * @return PeasantMessagesMonthlyChart|null
     * @throws \Exception
     */
    public function createPeasantMessagesMonthlyChart(int $userId = null)
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
            ->where('cm.paid', true)
            ->where('cm.created_at', '>=', '2020-05-01 00:00:00');


        if (null  == $userId) {
            $query->where('ru.role_id', User::TYPE_PEASANT);
        } else {
            $query->where('u.id', $userId);
        }

        $query->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return AveragePeasantMessagesPerHourChart()|null
     * @throws \Exception
     */
    public function createAveragePeasantMessagesPerHourInPeriodChart($startDate, $endDate, int $userId = null)
    {
        $query = \DB::table('conversation_messages as cm')
            ->select(
                \DB::raw(
                    'DATE_FORMAT(CONVERT_TZ(cm.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%H\') as creationHour, 
                    (COUNT(cm.id) / DATEDIFF("' .  $endDate->format('Y-m-d') . '", "' .  $startDate->format('Y-m-d') . '")) AS messagesAverageInHour'
                )
            )
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('cm.paid', true)
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ]);


        if (null  == $userId) {
            $query->where('ru.role_id', User::TYPE_PEASANT);
        } else {
            $query->where('u.id', $userId);
        }

        $query->groupBy('creationHour')
            ->orderBy('creationHour', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

        $labels = [];
        $counts = [];

        $hoursWithMessages = [];
        $messagesPerHour = [];
        foreach ($results as $result) {
            $hoursWithMessages[] = explode(' ', $result->creationHour)[0];
            $messagesPerHour[explode(' ', $result->creationHour)[0]] = $result->messagesAverageInHour;
        }

        $period = new DatePeriod(
            new DateTime($hoursWithMessages[0] . ':00'),
            new DateInterval('PT1H'),
            new DateTime('24:00')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('H');

            if (in_array($value->format('H'), $hoursWithMessages)) {
                $counts[] = $messagesPerHour[$value->format('H')];
            } else {
                $counts[] = 0;
            }
        }

        $averagePeasantMessagesPerHourChart = new AveragePeasantMessagesPerHourChart();
        $averagePeasantMessagesPerHourChart->labels($labels);
        $averagePeasantMessagesPerHourChart
            ->dataset('Average peasant messages in hour', 'line', $counts)
            ->backGroundColor('#de3e7b');

        $averagePeasantMessagesPerHourChart
            ->title('Average peasant messages per hour (past 10 days)');

        return $averagePeasantMessagesPerHourChart;
    }

    /**
     * @return PaymentsChart|null
     * @throws \Exception
     */
    public function createPaymentsChart($since)
    {
        $query = \DB::table('payments as p')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, COUNT(p.id) AS paymentsCount'))
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->where('p.created_at', '>=', $since)
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return PaymentsMonthlyChart|null
     * @throws \Exception
     */
    public function createPaymentsMonthlyChart()
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
            ->where('p.created_at', '>=', '2020-05-01 00:00:00')
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return RegistrationsChart|null
     * @throws \Exception
     */
    public function createGoogleLeadsChart()
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\')) as registrationDate, COUNT(u.id) AS registrationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->leftJoin('user_affiliate_tracking as uat', 'uat.user_id', 'u.id')
            ->where('uat.affiliate', UserAffiliateTracking::AFFILIATE_GOOGLE)
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.created_at', '>=', '2020-05-23 00:00:00')
            ->groupBy('registrationDate')
            ->orderBy('registrationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

        $labels = [];
        $counts = [];

        $datesWithGoogleLeads = [];
        $googleLeadsPerDate = [];
        foreach ($results as $result) {
            $datesWithGoogleLeads[] = explode(' ', $result->registrationDate)[0];
            $googleLeadsPerDate[explode(' ', $result->registrationDate)[0]] = $result->registrationsCount;
        }

        $period = new DatePeriod(
            new DateTime($datesWithGoogleLeads[0]),
            new DateInterval('P1D'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithGoogleLeads)) {
                $counts[] = $googleLeadsPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $googleLeadsChart = new RegistrationsChart();
        $googleLeadsChart->labels($labels);
        $googleLeadsChart
            ->dataset('Google Ads Leads on date', 'line', $counts)
            ->backGroundColor('#deb33e');

        $googleLeadsChart
            ->title('Google Ads Leads per day');

        return $googleLeadsChart;
    }


    /**
     * @return RegistrationsChart|null
     * @throws \Exception
     */
    public function createRegistrationsChart($since)
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\')) as registrationDate, COUNT(u.id) AS registrationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.created_at', '>=', $since)
            ->groupBy('registrationDate')
            ->orderBy('registrationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return RegistrationsMonthlyChart|null
     * @throws \Exception
     */
    public function createRegistrationsMonthlyChart()
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
            ->where('u.created_at', '>=', '2020-05-01 00:00:00')
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return RevenueChart|null
     * @throws \Exception
     */
    public function createRevenueChart($since)
    {
        $query = \DB::table('payments as p')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, SUM(p.amount) as revenueOnDay'))
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->where('p.created_at', '>=', $since)
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return RevenueChart|null
     * @throws \Exception
     */
    public function createAffiliateRevenueChart(string $affiliate)
    {
        $query = \DB::table('payments as p')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, SUM(p.amount) as revenueOnDay'))
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('user_affiliate_tracking as uat', 'uat.user_id', 'u.id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->where('uat.affiliate', $affiliate)
            ->where('p.created_at', '>=', '2020-05-23 00:00:00')
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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

        $affiliateName = $affiliate === UserAffiliateTracking::AFFILIATE_GOOGLE ? 'Google Ads' : 'Xpartners';

        $revenueChart
            ->dataset($affiliateName . ' revenue on date', 'line', $counts)
            ->backGroundColor('#339929');

        $revenueChart
            ->title($affiliateName . ' revenue per day');

        return $revenueChart;
    }

    /**
     * @return ConversionsChart()|null
     * @throws \Exception
     */
    public function createXpartnersConversionsChart()
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, COUNT(DISTINCT(u.id)) as conversionsOnDate'))
            ->leftJoin('payments as p', 'u.id', 'p.user_id')
            ->leftJoin('user_affiliate_tracking as uat', 'uat.user_id', 'u.id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->where('uat.affiliate', 'xpartners')
            ->where('p.created_at', '>=', '2020-05-23 00:00:00')
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

        $labels = [];
        $counts = [];

        $datesWithConversions = [];
        $conversionsPerDate = [];

        foreach ($results as $result) {
            $datesWithConversions[] = explode(' ', $result->creationDate)[0];
            $conversionsPerDate[explode(' ', $result->creationDate)[0]] = $result->conversionsOnDate;
        }

        $period = new DatePeriod(
            new DateTime($datesWithConversions[0]),
            new DateInterval('P1D'),
            new DateTime('now')
        );

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            $labels[] = $value->format('Y-m-d');

            if (in_array($value->format('Y-m-d'), $datesWithConversions)) {
                $counts[] = $conversionsPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $conversionsChart = new ConversionsChart();
        $conversionsChart->labels($labels);

        $conversionsChart
            ->dataset('X-Parterns conversions on date', 'line', $counts)
            ->backGroundColor('#6d6913');

        $conversionsChart
            ->title('X-Parterns conversions per day');

        return $conversionsChart;
    }

    /**
     * @return RevenueMonthlyChart|null
     * @throws \Exception
     */
    public function createRevenueMonthlyChart()
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
            ->where('p.created_at', '>=', '2020-05-01 00:00:00')
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

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
     * @return RevenueChart|null
     * @throws \Exception
     */
    public function createRevenueWithoutSalesTaxChart()
    {
        $query = \DB::table('payments as p')
            ->select(\DB::raw('DATE(CONVERT_TZ(p.created_at, \'UTC\', \'Europe/Amsterdam\')) as creationDate, SUM(p.amount) as revenueOnDay'))
            ->leftJoin('users as u', 'u.id', 'p.user_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('p.status', Payment::STATUS_COMPLETED)
            ->where('p.created_at', '>=', '2020-05-23 00:00:00')
            ->groupBy('creationDate')
            ->orderBy('creationDate', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

        $labels = [];
        $counts = [];

        $datesWithRevenue = [];
        $revenuePerDate = [];

        foreach ($results as $result) {
            $datesWithRevenue[] = explode(' ', $result->creationDate)[0];

            $revenueInCents = $result->revenueOnDay;
            $revenueInCentsWithoutSalesTax = $revenueInCents / (1 + self::SALES_TAX);

            $revenueWithoutSalesTaxPerDate[explode(' ', $result->creationDate)[0]] = (int)$revenueInCentsWithoutSalesTax / 100;
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
                $counts[] = $revenueWithoutSalesTaxPerDate[$value->format('Y-m-d')];
            } else {
                $counts[] = 0;
            }
        }

        $revenueChart = new RevenueChart();
        $revenueChart->labels($labels);

        $revenueChart
            ->dataset('Revenue without sales tax on date', 'line', $counts)
            ->backGroundColor('#339929');

        $revenueChart
            ->title('Revenue without sales tax per day');

        return $revenueChart;
    }

    /**
     * @return RevenueMonthlyChart|null
     * @throws \Exception
     */
    public function createRevenueWithoutSalesTaxMonthlyChart()
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
            ->where('p.created_at', '>=', '2020-05-01 00:00:00')
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        if ($results->count() === 0) {
            return null;
        }

        $labels = [];
        $counts = [];

        $monthsWithRevenue = [];
        $revenuePerMonth = [];

        foreach ($results as $result) {
            $monthsWithRevenue[] = explode(' ', $result->months)[0];

            $revenueInCents = $result->revenueInMonth;
            $revenueInCentsWithoutSalesTax = $revenueInCents / (1 + self::SALES_TAX);

            $revenueWithoutSalesTaxPerMonth[explode(' ', $result->months)[0]] = (int)$revenueInCentsWithoutSalesTax / 100;
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
                $counts[] = $revenueWithoutSalesTaxPerMonth[$value->format('Y-m')];
            } else {
                $counts[] = 0;
            }
        }

        $revenueMonthlyChart = new RevenueMonthlyChart();
        $revenueMonthlyChart->labels($labels);
        $revenueMonthlyChart
            ->dataset('Revenue without sales tax in month', 'bar', $counts)
            ->backGroundColor('#339929');

        $revenueMonthlyChart
            ->barWidth(self::BAR_WIDTH)
            ->title('Revenue without sales tax per month');

        return $revenueMonthlyChart;
    }


    /**
     * @return NetPeasantsAcquiredChart|null
     * @throws \Exception
     */
    public function createNetPeasantsAcquiredChart($since )
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\')) as registrationDate, COUNT(u.id) AS registrationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.created_at', '>=', $since)
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
            ->where('u.created_at', '>=', '2020-05-23 00:00:00')
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
    public function createNetPeasantsAcquiredMonthlyChart(): NetPeasantsAcquiredMonthlyChart
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
            ->where('u.created_at', '>=', '2020-05-01 00:00:00')
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
    public function createDeactivationsChart($since): DeactivationsChart
    {
        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE(CONVERT_TZ(u.deactivated_at, \'UTC\', \'Europe/Amsterdam\')) as deactivationDate, COUNT(u.id) AS deactivationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where('u.deactivated_at', '!=', null)
            ->where('u.deactivated_at', '>=', $since)
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
    public function createDeactivationsMonthlyChart(): DeactivationsMonthlyChart
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
            ->where('u.deactivated_at', '>=', '2020-05-01 00:00:00')
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

    /**
     * @return RevenueChart
     * @throws \Exception
     */
    public function createRpuChart(): RevenueChart
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

        $monthsWithRevenue = [];
        $revenuePerMonth = [];

        foreach ($results as $result) {
            $monthsWithRevenue[] = explode(' ', $result->months)[0];
            $revenuePerMonth[explode(' ', $result->months)[0]] = (int)$result->revenueInMonth / 100;
        }

        $query = \DB::table('users as u')
            ->select(\DB::raw('DATE_FORMAT(CONVERT_TZ(u.created_at, \'UTC\', \'Europe/Amsterdam\'), \'%Y-%m\') as months, COUNT(u.id) AS registrationsCount'))
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', User::TYPE_PEASANT)
            ->where(function ($query) {
                $query->where('deactivated_at', null);
                $query->orWhere('deactivated_at', '>=', 'months');
            })
            ->groupBy('months')
            ->orderBy('months', 'ASC');

        $results = $query->get();

        $registrationsUntilMonth = [];
        $registrationsCount = 0;

        foreach ($results as $result) {
            $registrationsUntilMonth[explode(' ', $result->months)[0]] = $registrationsCount + $result->registrationsCount;
            $registrationsCount += $result->registrationsCount;
        }

        $period = new DatePeriod(
            new DateTime($monthsWithRevenue[0]),
            new DateInterval('P1M'),
            new DateTime('now')
        );


        $mostRecentRegistrationSum = 0;
        foreach ($period as $key => $value) {
            if (!in_array($value->format('Y-m'), array_keys($registrationsUntilMonth))) {
                $registrationsUntilMonth[$value->format('Y-m')] = $mostRecentRegistrationSum;
            } else {
                $mostRecentRegistrationSum = $registrationsUntilMonth[$value->format('Y-m')];
            }
        }

        $labels = [];
        $counts = [];

        $lastMonth = null;
        $loopCount = 0;

        /**
         * @var  $key
         * @var DateTime $value
         */
        foreach ($period as $key => $value) {
            if ($loopCount >= 2) {
                $labels[] = $value->format('Y-m');

                if (in_array($value->format('Y-m'), $monthsWithRevenue)) {
                    $counts[] = number_format($revenuePerMonth[$value->format('Y-m')] / $registrationsUntilMonth[$lastMonth], 2);
                } else {
                    $counts[] = 0;
                }
            }

            $lastMonth = $value->format('Y-m');
            $loopCount++;
        }

        $revenueChart = new RevenueChart();
        $revenueChart->labels($labels);

        $revenueChart
            ->dataset('ARPU (Average Revenue Per User) in Month', 'bar', $counts)
            ->backGroundColor('#339929');

        $revenueChart
            ->barWidth(self::BAR_WIDTH)
            ->title('ARPU per month');

        return $revenueChart;
    }

    public function getMessagesCharts($peasants, $since) {
        $peasantMessagesCharts = [];
        $peasantMessagesMonthlyCharts = [];

        foreach ($peasants as $peasant) {
            $peasantMessagesCharts[] = $this->createPeasantMessagesChart($peasant->getId(), $since);
            $peasantMessagesMonthlyCharts[] = $this->createPeasantMessagesMonthlyChart($peasant->getId());
        }

        return [
            'daily' => $peasantMessagesCharts,
            'monthly' => $peasantMessagesMonthlyCharts
        ];
    }
}
