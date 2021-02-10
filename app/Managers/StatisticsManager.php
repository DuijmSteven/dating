<?php

namespace App\Managers;

use App\ConversationMessage;
use App\Creditpack;
use App\Expense;
use App\Facades\Helpers\PaymentsHelper;
use App\Payment;
use App\Role;
use App\User;
use App\UserAccount;
use App\UserAffiliateTracking;
use App\UserMeta;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsManager
{
    public function revenueBetween(
        $startDate,
        $endDate,
        $fromUsersRegisteredUntilDate = null,
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        $query = Payment::whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->where('status', Payment::STATUS_COMPLETED);

        if ($fromUsersRegisteredUntilDate) {
            $query->whereHas('peasant', function ($query) use ($fromUsersRegisteredUntilDate) {
                $query->where('created_at', '<=', $fromUsersRegisteredUntilDate);
            });
        }

        if ($affiliate) {
            $query->whereHas('peasant.affiliateTracking', function ($query) use ($fromUsersRegisteredUntilDate, $affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        if ($excludeAffiliate) {
            $query->whereDoesntHave('peasant.affiliateTracking', function ($query) use ($fromUsersRegisteredUntilDate, $excludeAffiliate) {
                $query->where('affiliate', $excludeAffiliate);
            });
        }

        return $query
            ->sum('amount');
    }

    public function unspentCredits($onlyUsersThatMessagedWithinLatestNumberOfDays = null)
    {
        $query = UserAccount
            ::whereHas('peasant', function ($query) {
                $query->where('active', true);
            })
            ->whereHas('peasant.payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            });


        if ($onlyUsersThatMessagedWithinLatestNumberOfDays) {
            $query->whereHas('peasant.messages', function ($query) use ($onlyUsersThatMessagedWithinLatestNumberOfDays) {
                $query->where('created_at', '>=', Carbon::now()->subDays($onlyUsersThatMessagedWithinLatestNumberOfDays));
            });
        }

        return $query
            ->sum('credits');
    }

    public function affiliateRevenueBetween(string $affiliate, $startDate, $endDate, $publisher = null)
    {
        return Payment::whereHas('peasant.affiliateTracking', function ($query) use ($affiliate, $publisher) {
            $query->where('affiliate', $affiliate);

            if ($publisher) {
                $query->where('publisher', $publisher);
            }
        })
            ->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }

    public function affiliateExpensesBetween(int $payee, int $type, $startDate, $endDate)
    {
        return Expense::where('payee', $payee)
            ->where('type', $type)
            ->whereBetween('takes_place_at',
                [
                    $startDate,
                    $endDate
                ])
            ->sum('amount');
    }

    public function getUserTypeStatistics()
    {
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $oneMonthAgo = Carbon::now('Europe/Amsterdam')->subMonths(1)->setTimezone('UTC');
        $twoMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(2)->setTimezone('UTC');
        $threeMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(3)->setTimezone('UTC');
        $fourMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(4)->setTimezone('UTC');
        $fiveMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(5)->setTimezone('UTC');
        $sixMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(6)->setTimezone('UTC');
        $sevenMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(7)->setTimezone('UTC');
        $eightMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(8)->setTimezone('UTC');
        $nineMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(9)->setTimezone('UTC');

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));

        $allTimePayingUsersCount = $this->payingUsersCreatedUntilDateCount($endOfToday);
        $payingUsersUntilNineMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($nineMonthsAgo);
        $payingUsersUntilEightMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($eightMonthsAgo);
        $payingUsersUntilSevenMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($sevenMonthsAgo);
        $payingUsersUntilSixMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($sixMonthsAgo);
        $payingUsersUntilFiveMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($fiveMonthsAgo);
        $payingUsersUntilFourMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($fourMonthsAgo);
        $payingUsersUntilThreeMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($threeMonthsAgo);
        $payingUsersUntilTwoMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($twoMonthsAgo);
        $payingUsersUntilOneMonthAgoCount = $this->payingUsersCreatedUntilDateCount($oneMonthAgo);

        $allUsersCount = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
        ->count();

        $allTimeRevenue = $this->revenueBetween(
            $launchDate,
            $endOfToday
        );

        $revenueFromUsersUntilNineMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $nineMonthsAgo
        );

        $revenueFromUsersUntilEightMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $eightMonthsAgo
        );

        $revenueFromUsersUntilSevenMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $sevenMonthsAgo
        );

        $revenueFromUsersUntilSixMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $sixMonthsAgo
        );

        $revenueFromUsersUntilFiveMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $fiveMonthsAgo
        );

        $revenueFromUsersUntilFourMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $fourMonthsAgo
        );

        $revenueFromUsersUntilThreeMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $threeMonthsAgo
        );

        $revenueFromUsersUntilTwoMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $twoMonthsAgo
        );

        $revenueFromUsersUntilOneMonthAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $oneMonthAgo
        );

        $averageRevenuePerAllTimeUser = $allTimeRevenue / $allUsersCount;
        $averageRevenuePerAllTimePayingUser = $allTimeRevenue / $allTimePayingUsersCount;
        $averageLifetimeValuePerUserRegisteredUntilNineMonthsAgo = $revenueFromUsersUntilNineMonthsAgo / $payingUsersUntilNineMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilEightMonthsAgo = $revenueFromUsersUntilEightMonthsAgo / $payingUsersUntilEightMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilSevenMonthsAgo = $revenueFromUsersUntilSevenMonthsAgo / $payingUsersUntilSevenMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilSixMonthsAgo = $revenueFromUsersUntilSixMonthsAgo / $payingUsersUntilSixMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilFiveMonthsAgo = $revenueFromUsersUntilFiveMonthsAgo / $payingUsersUntilFiveMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilFourMonthsAgo = $revenueFromUsersUntilFourMonthsAgo / $payingUsersUntilFourMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilThreeMonthsAgo = $revenueFromUsersUntilThreeMonthsAgo / $payingUsersUntilThreeMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilTwoMonthsAgo = $revenueFromUsersUntilTwoMonthsAgo / $payingUsersUntilTwoMonthsAgoCount;
        $averageLifetimeValuePerUserRegisteredUntilOneMonthAgo = $revenueFromUsersUntilOneMonthAgo / $payingUsersUntilOneMonthAgoCount;

        $peasantsWithCreditpack = $this->peasantsWithCreditpack();

        return [
            'no_credits' => $this->peasantsWithNoCreditpackCount(),
            'never_bought' => $this->peasantsThatNeverHadCreditpackCount(),
            'small' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::SMALL
            ),
            'medium' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::MEDIUM
            ),
            'large' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::LARGE
            ),
            'xl' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::XL
            ),
            'allTimePayingUsersCount' => $allTimePayingUsersCount,
            'payingUsersRegisteredUntilNineMonthsAgoCount' => $payingUsersUntilNineMonthsAgoCount,
            'payingUsersRegisteredUntilEightMonthsAgoCount' => $payingUsersUntilEightMonthsAgoCount,
            'payingUsersRegisteredUntilSevenMonthsAgoCount' => $payingUsersUntilSevenMonthsAgoCount,
            'payingUsersRegisteredUntilSixMonthsAgoCount' => $payingUsersUntilSixMonthsAgoCount,
            'payingUsersRegisteredUntilFiveMonthsAgoCount' => $payingUsersUntilFiveMonthsAgoCount,
            'payingUsersRegisteredUntilFourMonthsAgoCount' => $payingUsersUntilFourMonthsAgoCount,
            'payingUsersRegisteredUntilThreeMonthsAgoCount' => $payingUsersUntilThreeMonthsAgoCount,
            'payingUsersRegisteredUntilTwoMonthsAgoCount' => $payingUsersUntilTwoMonthsAgoCount,
            'payingUsersRegisteredUntilOneMonthAgoCount' => $payingUsersUntilOneMonthAgoCount,
            'averageRevenuePerAllTimePayingUser' => number_format($averageRevenuePerAllTimePayingUser / 100, 2),
            'averageRevenuePerUser' => number_format($averageRevenuePerAllTimeUser / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilNineMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilNineMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilEightMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilEightMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilSevenMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilSevenMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilSixMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilSixMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilFiveMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilFiveMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilFourMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilFourMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilThreeMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilThreeMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilTwoMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilTwoMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilOneMonthAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilOneMonthAgo / 100, 2),
        ];
    }

    public function getAffiliateLvuStatistics(string $affiliate = UserAffiliateTracking::AFFILIATE_GOOGLE)
    {
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $tenDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(10)->setTimezone('UTC');
        $twentyDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(20)->setTimezone('UTC');
        $oneMonthAgo = Carbon::now('Europe/Amsterdam')->subMonths(1)->setTimezone('UTC');
        $oneAndAHalfMonthAgo = Carbon::now('Europe/Amsterdam')->subDays(45)->setTimezone('UTC');
        $twoMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(2)->setTimezone('UTC');
        $threeMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(3)->setTimezone('UTC');
        $fourMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(4)->setTimezone('UTC');
        $fiveMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(5)->setTimezone('UTC');
        $sixMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(6)->setTimezone('UTC');
        $sevenMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(7)->setTimezone('UTC');
        $eightMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(8)->setTimezone('UTC');
        $nineMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(9)->setTimezone('UTC');

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));

        $allTimePayingUsersCount = $this->payingUsersCreatedUntilDateCount($endOfToday, $affiliate);
        $payingUsersUntilNineMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($nineMonthsAgo, $affiliate);
        $payingUsersUntilEightMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($eightMonthsAgo, $affiliate);
        $payingUsersUntilSevenMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($sevenMonthsAgo, $affiliate);
        $payingUsersUntilSixMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($sixMonthsAgo, $affiliate);
        $payingUsersUntilFiveMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($fiveMonthsAgo, $affiliate);
        $payingUsersUntilFourMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($fourMonthsAgo, $affiliate);
        $payingUsersUntilThreeMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($threeMonthsAgo, $affiliate);
        $payingUsersUntilTwoMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($twoMonthsAgo, $affiliate);
        $payingUsersUntilOneMonthAndAHalfAgoCount = $this->payingUsersCreatedUntilDateCount($oneAndAHalfMonthAgo, $affiliate);
        $payingUsersUntilOneMonthAgoCount = $this->payingUsersCreatedUntilDateCount($oneMonthAgo, $affiliate);
        $payingUsersUntilTwentyDaysAgoCount = $this->payingUsersCreatedUntilDateCount($twentyDaysAgo, $affiliate);
        $payingUsersUntilTenDaysAgoCount = $this->payingUsersCreatedUntilDateCount($tenDaysAgo, $affiliate);

        $allUsersCount = User::whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            })
            ->count();

        $allTimeRevenue = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            null,
            $affiliate
        );

        $revenueFromUsersUntilNineMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $nineMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilEightMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $eightMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilSevenMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $sevenMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilSixMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $sixMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilFiveMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $fiveMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilFourMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $fourMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilThreeMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $threeMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilTwoMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $twoMonthsAgo,
            $affiliate
        );

        $revenueFromUsersUntilOneMonthAndAHalfAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $oneAndAHalfMonthAgo,
            $affiliate
        );

        $revenueFromUsersUntilOneMonthAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $oneMonthAgo,
            $affiliate
        );

//        $revenueFromUsersUntilTwentyDaysAgo = $this->revenueBetween(
//            $launchDate,
//            $endOfToday,
//            $twentyDaysAgo,
//            $affiliate
//        );
//
//        $revenueFromUsersUntilTenDaysAgo = $this->revenueBetween(
//            $launchDate,
//            $endOfToday,
//            $tenDaysAgo,
//            $affiliate
//        );

        $alvPerUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allUsersCount);
        $alvPerPayingUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allTimePayingUsersCount);
        $alvPerPayingUserRegisteredUntilNineMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilNineMonthsAgo, $payingUsersUntilNineMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilEightMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilEightMonthsAgo, $payingUsersUntilEightMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilSevenMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilSevenMonthsAgo, $payingUsersUntilSevenMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilSixMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilSixMonthsAgo, $payingUsersUntilSixMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilFiveMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilFiveMonthsAgo, $payingUsersUntilFiveMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilFourMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilFourMonthsAgo, $payingUsersUntilFourMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilThreeMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilThreeMonthsAgo, $payingUsersUntilThreeMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilTwoMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwoMonthsAgo, $payingUsersUntilTwoMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAndAHalfAgo, $payingUsersUntilOneMonthAndAHalfAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAgo, $payingUsersUntilOneMonthAgoCount);
//        $alvPerPayingUserRegisteredUntilTwentyDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwentyDaysAgo, $payingUsersUntilTwentyDaysAgoCount);
//        $alvPerPayingUserRegisteredUntilTenDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTenDaysAgo, $payingUsersUntilTenDaysAgoCount);

        $peasantsWithCreditpack = $this->peasantsWithCreditpack($affiliate);

        return [
            'no_credits' => $this->peasantsWithNoCreditpackCount($affiliate),
            'never_bought' => $this->peasantsThatNeverHadCreditpackCount($affiliate),
            'small' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::SMALL
            ),
            'medium' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::MEDIUM
            ),
            'large' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::LARGE
            ),
            'xl' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::XL
            ),
            'allTimePayingUsersCount' => $allTimePayingUsersCount,
            'payingUsersRegisteredUntilNineMonthsAgoCount' => $payingUsersUntilNineMonthsAgoCount,
            'payingUsersRegisteredUntilEightMonthsAgoCount' => $payingUsersUntilEightMonthsAgoCount,
            'payingUsersRegisteredUntilSevenMonthsAgoCount' => $payingUsersUntilSevenMonthsAgoCount,
            'payingUsersRegisteredUntilSixMonthsAgoCount' => $payingUsersUntilSixMonthsAgoCount,
            'payingUsersRegisteredUntilFiveMonthsAgoCount' => $payingUsersUntilFiveMonthsAgoCount,
            'payingUsersRegisteredUntilFourMonthsAgoCount' => $payingUsersUntilFourMonthsAgoCount,
            'payingUsersRegisteredUntilThreeMonthsAgoCount' => $payingUsersUntilThreeMonthsAgoCount,
            'payingUsersRegisteredUntilTwoMonthsAgoCount' => $payingUsersUntilTwoMonthsAgoCount,
            'payingUsersRegisteredUntilOneMonthAndAHalfAgoCount' => $payingUsersUntilOneMonthAndAHalfAgoCount,
            'payingUsersRegisteredUntilOneMonthAgoCount' => $payingUsersUntilOneMonthAgoCount,
            'payingUsersRegisteredUntilTwentyDaysAgoCount' => $payingUsersUntilTwentyDaysAgoCount,
            'payingUsersRegisteredUntilTenDaysAgoCount' => $payingUsersUntilTenDaysAgoCount,
            'alvPerPayingUserRegistered' => number_format($alvPerPayingUserRegisteredAllTime / 100, 2),
            'alvPerUserRegistered' => number_format($alvPerUserRegisteredAllTime / 100, 2),
            'alvPerUserRegisteredUntilNineMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilNineMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilEightMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilEightMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilSevenMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilSevenMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilSixMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilSixMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilFiveMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilFiveMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilFourMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilFourMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilThreeMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilThreeMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilTwoMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilTwoMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAndAHalfAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAgo / 100, 2),
//            'alvPerUserRegisteredUntilTwentyDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTwentyDaysAgo / 100, 2),
//            'alvPerUserRegisteredUntilTenDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTenDaysAgo / 100, 2),
        ];
    }

    public function getExcludingXpartnersLvuStatistics()
    {
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $tenDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(10)->setTimezone('UTC');
        $twentyDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(20)->setTimezone('UTC');
        $oneMonthAgo = Carbon::now('Europe/Amsterdam')->subMonths(1)->setTimezone('UTC');
        $oneAndAHalfMonthAgo = Carbon::now('Europe/Amsterdam')->subDays(45)->setTimezone('UTC');
        $twoMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(2)->setTimezone('UTC');
        $threeMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(3)->setTimezone('UTC');
        $fourMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(4)->setTimezone('UTC');
        $fiveMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(5)->setTimezone('UTC');
        $sixMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(6)->setTimezone('UTC');
        $sevenMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(7)->setTimezone('UTC');
        $eightMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(8)->setTimezone('UTC');
        $nineMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(9)->setTimezone('UTC');

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', config('app.launch_date'));

        $allTimePayingUsersCount = $this->payingUsersCreatedUntilDateCount(
            $endOfToday,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilNineMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $nineMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilEightMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $eightMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilSevenMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $sevenMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilSixMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $sixMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilFiveMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $fiveMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilFourMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $fourMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilThreeMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $threeMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilTwoMonthsAgoCount = $this->payingUsersCreatedUntilDateCount(
            $twoMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilOneMonthAndAHalfAgoCount = $this->payingUsersCreatedUntilDateCount(
            $oneAndAHalfMonthAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilOneMonthAgoCount = $this->payingUsersCreatedUntilDateCount(
            $oneMonthAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilTwentyDaysAgoCount = $this->payingUsersCreatedUntilDateCount(
            $twentyDaysAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $payingUsersUntilTenDaysAgoCount = $this->payingUsersCreatedUntilDateCount(
            $tenDaysAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $allUsersCount = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->whereDoesntHave('affiliateTracking', function ($query) {
                $query->where('affiliate', UserAffiliateTracking::AFFILIATE_XPARTNERS);
            })
            ->count();

        $allTimeRevenue = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            null,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilNineMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $nineMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilEightMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $eightMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilSevenMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $sevenMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilSixMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $sixMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilFiveMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $fiveMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilFourMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $fourMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilThreeMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $threeMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilTwoMonthsAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $twoMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilOneMonthAndAHalfAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $oneAndAHalfMonthAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilOneMonthAgo = $this->revenueBetween(
            $launchDate,
            $endOfToday,
            $oneMonthAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

//        $revenueFromUsersUntilTwentyDaysAgo = $this->revenueBetween(
//            $launchDate,
//            $endOfToday,
//            $twentyDaysAgo,
//            null,
//            UserAffiliateTracking::AFFILIATE_XPARTNERS
//        );
//
//        $revenueFromUsersUntilTenDaysAgo = $this->revenueBetween(
//            $launchDate,
//            $endOfToday,
//            $tenDaysAgo,
//            null,
//            UserAffiliateTracking::AFFILIATE_XPARTNERS
//        );

        $alvPerUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allUsersCount);
        $alvPerPayingUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allTimePayingUsersCount);
        $alvPerPayingUserRegisteredUntilNineMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilNineMonthsAgo, $payingUsersUntilNineMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilEightMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilEightMonthsAgo, $payingUsersUntilEightMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilSevenMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilSevenMonthsAgo, $payingUsersUntilSevenMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilSixMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilSixMonthsAgo, $payingUsersUntilSixMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilFiveMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilFiveMonthsAgo, $payingUsersUntilFiveMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilFourMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilFourMonthsAgo, $payingUsersUntilFourMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilThreeMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilThreeMonthsAgo, $payingUsersUntilThreeMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilTwoMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwoMonthsAgo, $payingUsersUntilTwoMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAndAHalfAgo, $payingUsersUntilOneMonthAndAHalfAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAgo, $payingUsersUntilOneMonthAgoCount);
//        $alvPerPayingUserRegisteredUntilTwentyDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwentyDaysAgo, $payingUsersUntilTwentyDaysAgoCount);
//        $alvPerPayingUserRegisteredUntilTenDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTenDaysAgo, $payingUsersUntilTenDaysAgoCount);

        $peasantsWithCreditpack = $this->peasantsWithCreditpack(null, UserAffiliateTracking::AFFILIATE_XPARTNERS);

        return [
            'no_credits' => $this->peasantsWithNoCreditpackCount(null, UserAffiliateTracking::AFFILIATE_XPARTNERS),
            'never_bought' => $this->peasantsThatNeverHadCreditpackCount(null, UserAffiliateTracking::AFFILIATE_XPARTNERS),
            'small' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::SMALL
            ),
            'medium' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::MEDIUM
            ),
            'large' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::LARGE
            ),
            'xl' => $this->filterPeasantsWithCreditpackIdCount(
                $peasantsWithCreditpack,
                Creditpack::XL
            ),
            'allTimePayingUsersCount' => $allTimePayingUsersCount,
            'payingUsersRegisteredUntilNineMonthsAgoCount' => $payingUsersUntilNineMonthsAgoCount,
            'payingUsersRegisteredUntilEightMonthsAgoCount' => $payingUsersUntilEightMonthsAgoCount,
            'payingUsersRegisteredUntilSevenMonthsAgoCount' => $payingUsersUntilSevenMonthsAgoCount,
            'payingUsersRegisteredUntilSixMonthsAgoCount' => $payingUsersUntilSixMonthsAgoCount,
            'payingUsersRegisteredUntilFiveMonthsAgoCount' => $payingUsersUntilFiveMonthsAgoCount,
            'payingUsersRegisteredUntilFourMonthsAgoCount' => $payingUsersUntilFourMonthsAgoCount,
            'payingUsersRegisteredUntilThreeMonthsAgoCount' => $payingUsersUntilThreeMonthsAgoCount,
            'payingUsersRegisteredUntilTwoMonthsAgoCount' => $payingUsersUntilTwoMonthsAgoCount,
            'payingUsersRegisteredUntilOneMonthAndAHalfAgoCount' => $payingUsersUntilOneMonthAndAHalfAgoCount,
            'payingUsersRegisteredUntilOneMonthAgoCount' => $payingUsersUntilOneMonthAgoCount,
            'payingUsersRegisteredUntilTwentyDaysAgoCount' => $payingUsersUntilTwentyDaysAgoCount,
            'payingUsersRegisteredUntilTenDaysAgoCount' => $payingUsersUntilTenDaysAgoCount,
            'alvPerPayingUserRegistered' => number_format($alvPerPayingUserRegisteredAllTime / 100, 2),
            'alvPerUserRegistered' => number_format($alvPerUserRegisteredAllTime / 100, 2),
            'alvPerUserRegisteredUntilNineMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilNineMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilEightMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilEightMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilSevenMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilSevenMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilSixMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilSixMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilFiveMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilFiveMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilFourMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilFourMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilThreeMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilThreeMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilTwoMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilTwoMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAndAHalfAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAgo / 100, 2),
//            'alvPerUserRegisteredUntilTwentyDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTwentyDaysAgo / 100, 2),
//            'alvPerUserRegisteredUntilTenDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTenDaysAgo / 100, 2),
        ];
    }

    public function calculateAverageRevenuePerUser(int $revenue, int $userCount)
    {
        if ($userCount === 0) {
            return 0;
        }

        return $revenue / $userCount;
    }

    public function payingUsersCreatedUntilDateCount($date, $affiliate = null, $excludeAffiliate = null)
    {
        return $this->payingUsersCreatedUntilDateQuery($date, $affiliate, $excludeAffiliate)
            ->count();
    }

    public function payingUsersCreatedUntilDateQuery($date, $affiliate = null, $excludeAffiliate = null)
    {
        $query = User::whereHas('payments', function ($query) {
            $query->where('status', Payment::STATUS_COMPLETED);
        })
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->where('created_at', '<=', $date);

        if ($affiliate) {
            $query->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        if ($excludeAffiliate) {
            $query->whereDoesntHave('affiliateTracking', function ($query) use ($excludeAffiliate) {
                $query->where('affiliate', $excludeAffiliate);
            });
        }

        return $query;
    }

    public function payingUsersCreatedUntilDate($date)
    {
        return $this->payingUsersCreatedUntilDateQuery($date)
            ->get();
    }

    public function affiliateConversionsBetweenQueryBuilder(string $affiliate, $startDate, $endDate, $publisher = null)
    {
        $query = Payment
            ::with([
                'peasant',
                'peasant.meta',
                'peasant.account',
                'peasant.payments',
                'peasant.completedPayments',
                'peasant.affiliateTracking',
                'peasant.conversationsAsUserA',
                'peasant.conversationsAsUserB',
            ])
            ->where('is_conversion', true)
            ->whereBetween('created_at',
            [
                $startDate,
                $endDate
            ]);

        if ($affiliate !== 'any') {
            $query->whereHas('peasant.affiliateTracking', function ($query) use ($affiliate, $publisher) {
                $query->where('affiliate', $affiliate);

                if ($publisher) {
                    $query->where('publisher', $publisher);
                }
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    public function affiliateConversionsBetweenCount(string $affiliate, $startDate, $endDate, $publisher = null)
    {
        return $this->affiliateConversionsBetweenQueryBuilder($affiliate, $startDate, $endDate, $publisher)
            ->count();
    }

    public function registrationsCountBetween($startDate, $endDate)
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
            ->count();
    }

    public function messagesSentCountBetween($startDate, $endDate)
    {
        return ConversationMessage::whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->count();
    }

    public function messagesSentByUserTypeBetweenQueryBuilder(int $userType, $startDate, $endDate, $affiliate = null)
    {

        $query = DB::table('conversation_messages as cm')
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id');

        if ($affiliate) {
            $query->leftJoin('user_affiliate_tracking as uat', 'u.id', 'uat.user_id')
                ->where('uat.affiliate', $affiliate);
        }

        $query->where('ru.role_id', $userType)
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ]);

        return $query;
    }

    public function publicChatMessagesSentByUserTypeBetweenQueryBuilder(int $userType, $startDate, $endDate)
    {

        return DB::table('public_chat_items as pci')
            ->leftJoin('users as u', 'u.id', 'pci.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', $userType)
            ->whereBetween('pci.created_at', [
                $startDate,
                $endDate
            ]);
    }

    public function messagesSentByUserTypeBetween(int $userType, $startDate, $endDate)
    {
        return ConversationMessage::whereHas('sender.roles', function ($query) use ($userType) {
            $query->where('id', $userType);
        })->whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->get();
    }

    public function messagesSentByUserTypeCountBetween(int $userType, $startDate, $endDate)
    {
        return $this->messagesSentByUserTypeBetweenQueryBuilder($userType, $startDate, $endDate)
            ->count();
    }

    public function paidMessagesSentCount($startDate, $endDate, $affiliate = null)
    {
        $query = DB::table('conversation_messages as cm')
            ->where('cm.paid', true)
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ]);

        if ($affiliate) {
            $query
                ->leftJoin('users as u', 'u.id', 'cm.sender_id')
                ->leftJoin('user_affiliate_tracking as uat', 'u.id', 'uat.user_id')
                ->where('uat.affiliate', $affiliate);
        }

        return $query
            ->count();
    }

    public function paidMessagesSentByUserTypeLastHour()
    {
        $oneHourAgo = Carbon::now('Europe/Amsterdam')->subHours(1)->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesLastHour = $this->paidMessagesSentCount(
            $oneHourAgo,
            $now
        );

        if ($messagesLastHour === 0) {
            return 'No messages';
        }

        return $messagesLastHour;
    }

    public function messagesSentByUserTypePerHourToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesTodayCount = $this->paidMessagesSentCount(
            $startOfToday,
            $now
        );

        if ($messagesTodayCount === 0) {
            return 'No messages';
        }

        if ($now->diffInMinutes($startOfToday) === 0) {
            return 'Not available until 00:01am';
        }

        return number_format(($messagesTodayCount / $now->diffInMinutes($startOfToday)) * 60, 0);
    }

    public function messagesSentByUserTypePerHourCurrentMonth()
    {
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        $messagesCurrentMonth = $this->paidMessagesSentCount(
            $startOfMonth,
            $endOfMonth
        );

        if ($messagesCurrentMonth === 0) {
            return 'No messages';
        }

        if (Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfMonth) === 0) {
            return 'Not available until 1am';
        }

        return number_format($messagesCurrentMonth / Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfMonth), 0);
    }

    public function messagesSentByUserTypePerHourCurrentWeek()
    {
        $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        $messagesCurrentWeek = $this->paidMessagesSentCount(
            $startOfWeek,
            $endOfWeek
        );

        if ($messagesCurrentWeek === 0) {
            return 'No messages';
        }

        if (Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfWeek) === 0) {
            return 'Not available until 1am';
        }

        return number_format($messagesCurrentWeek / Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfWeek), 0);
    }

    public function messagesSentByUserTypePerHourCurrentYear()
    {
        $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesCurrentYear = $this->paidMessagesSentCount(
            $startOfYear,
            $endOfToday
        );

        if ($messagesCurrentYear === 0) {
            return 'No messages';
        }

        if ($now->diffInHours($startOfYear) === 0) {
            return 'Not available until 1am';
        }

        return number_format($messagesCurrentYear / $now->diffInHours($startOfYear), 0);
    }

    public function messagesSentByUserTypePerHourLastYear()
    {
        $startOfLastYear = Carbon::now('Europe/Amsterdam')->subYears(1)->startOfYear()->setTimezone('UTC');
        $endOfLastYear = Carbon::now('Europe/Amsterdam')->subYears(1)->endOfYear()->setTimezone('UTC');

        $messagesLastYear = $this->paidMessagesSentCount(
            $startOfLastYear,
            $endOfLastYear
        );

        if ($messagesLastYear === 0) {
            return 'No messages';
        }

        return number_format($messagesLastYear / $endOfLastYear->diffInHours($startOfLastYear), 0);
    }

    public function publicChatMessagesSentByUserTypeCountBetween(int $userType, $startDate, $endDate)
    {
        return $this->publicChatMessagesSentByUserTypeBetweenQueryBuilder($userType, $startDate, $endDate)
            ->count();
    }

    public function peasantDeactivationsCountBetween($startDate, $endDate)
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })->whereBetween('deactivated_at',
            [
                $startDate,
                $endDate
            ])
            ->where('deactivated_at', '!=', null)
            ->count();
    }

    public function peasantsWithNoCreditpackQueryBuilder(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        $query = User::where('active', true)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })->whereHas('account', function ($query) {
                $query->where('credits', 0);
            });

        if ($affiliate) {
            $query->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        if ($excludeAffiliate) {
            $query->whereDoesntHave('affiliateTracking', function ($query) use ($excludeAffiliate) {
                $query->where('affiliate', $excludeAffiliate);
            });
        }

        return $query;
    }

    public function peasantsWithNoCreditpack(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        return $this->peasantsWithNoCreditpackQueryBuilder($affiliate, $excludeAffiliate)->get();
    }

    public function peasantsWithNoCreditpackCount(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        return $this->peasantsWithNoCreditpackQueryBuilder($affiliate, $excludeAffiliate)->count();
    }

    public function peasantsThatNeverHadCreditpackQueryBuilder(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        $query = User::where('active', true)
            ->where(function ($query) {
                $query
                    ->has('payments', '=', 0)
                    ->whereHas('roles', function ($query) {
                        $query->where('id', User::TYPE_PEASANT);
                    })
                    ->orWhereHas('payments', function ($query) {
                        $query->whereNull('creditpack_id');
                    });
                });

        if ($affiliate) {
            $query->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        if ($excludeAffiliate) {
            $query->whereDoesntHave('affiliateTracking', function ($query) use ($excludeAffiliate) {
                $query->where('affiliate', $excludeAffiliate);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    public function peasantsThatNeverHadCreditpack(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        return $this->peasantsThatNeverHadCreditpackQueryBuilder($affiliate, $excludeAffiliate)->get();
    }

    public function peasantsThatNeverHadCreditpackCount(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        return $this->peasantsThatNeverHadCreditpackQueryBuilder($affiliate, $excludeAffiliate)->count();
    }

    public function topMessagersBetweenDates($startDate, $endDate, int $amount)
    {
        return User::with(['profileImage', 'account', 'messages' => function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->whereHas('messages', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            })
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messages->count();
            })
            ->take($amount);
    }

    public function peasantMessagersOnARoll($startDate, $endDate, int $amount, $countLimit = 1)
    {
        return User::with(['account', 'messages' => function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->whereHas('messages', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            })
            ->get()
            ->filter(function ($user) use ($countLimit) {
                return $user->messages->count() >= $countLimit;
            })
            ->sortByDesc(function ($user) {
                return $user->messages->count();
            })
            ->take($amount);
    }

    public function topOperatorMessagersBetweenDates($startDate, $endDate, int $amount)
    {
        return User::with(['messagesAsOperator' => function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }])
            ->whereHas('roles', function ($query) {
                $query->whereIn('id', [User::TYPE_OPERATOR, User::TYPE_ADMIN]);
            })
            ->whereHas('messagesAsOperator', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            })
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messagesAsOperator->count();
            })
            ->take($amount);
    }

    public function peasantsWithCreditpackQueryBuilder(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        $query = User::where('active', true)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })->whereHas('account', function ($query) {
                $query->where('credits', '>', 0);
            })->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'desc')->take(1);
            });

        if ($affiliate) {
            $query->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        if ($excludeAffiliate) {
            $query->whereDoesntHave('affiliateTracking', function ($query) use ($excludeAffiliate) {
                $query->where('affiliate', $excludeAffiliate);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    public function peasantsWithCreditpack(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        return $this->peasantsWithCreditpackQueryBuilder($affiliate, $excludeAffiliate)->get();
    }

    public function peasantsWithCreditpackCount(
        $affiliate = null,
        $excludeAffiliate = null
    ) {
        return $this->peasantsWithCreditpackQueryBuilder($affiliate, $excludeAffiliate)->count();
    }

    /**
     * @param Collection $peasants
     * @param int $creditpackId
     * @return Collection
     */
    public function filterPeasantsWithCreditpackId(Collection $peasants, int $creditpackId)
    {
        return $peasants->filter(function ($user) use ($creditpackId) {
            return $user->payments[count($user->payments) - 1]->getCreditpackId() == $creditpackId;
        });
    }

    public function filterPeasantsWithCreditpackIdCount(Collection $peasants, int $creditpackId)
    {
        return $this->filterPeasantsWithCreditpackId($peasants, $creditpackId)->count();
    }
}
