<?php

namespace App\Managers;

use App\ConversationMessage;
use App\Creditpack;
use App\Expense;
use App\Facades\Helpers\PaymentsHelper;
use App\Payment;
use App\User;
use App\UserAffiliateTracking;
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

    public function affiliateRevenueBetween(string $affiliate, $startDate, $endDate)
    {
        return Payment::whereHas('peasant.affiliateTracking', function ($query) use ($affiliate) {
            $query->where('affiliate', $affiliate);
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

        $allTimePayingUsersCount = $this->payingUsersCreatedUntilDateCount($endOfToday);
        $payingUsersUntilFourMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($fourMonthsAgo);
        $payingUsersUntilThreeMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($threeMonthsAgo);
        $payingUsersUntilTwoMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($twoMonthsAgo);
        $payingUsersUntilOneMonthAgoCount = $this->payingUsersCreatedUntilDateCount($oneMonthAgo);

        $allUsersCount = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
        ->count();

        $allTimeRevenue = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday
        );

        $revenueFromUsersUntilFourMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $fourMonthsAgo
        );

        $revenueFromUsersUntilThreeMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo
        );

        $revenueFromUsersUntilTwoMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo
        );

        $revenueFromUsersUntilOneMonthAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo
        );

        $averageRevenuePerAllTimeUser = $allTimeRevenue / $allUsersCount;
        $averageRevenuePerAllTimePayingUser = $allTimeRevenue / $allTimePayingUsersCount;
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
            'payingUsersRegisteredUntilFourMonthsAgoCount' => $payingUsersUntilFourMonthsAgoCount,
            'payingUsersRegisteredUntilThreeMonthsAgoCount' => $payingUsersUntilThreeMonthsAgoCount,
            'payingUsersRegisteredUntilTwoMonthsAgoCount' => $payingUsersUntilTwoMonthsAgoCount,
            'payingUsersRegisteredUntilOneMonthAgoCount' => $payingUsersUntilOneMonthAgoCount,
            'averageRevenuePerAllTimePayingUser' => number_format($averageRevenuePerAllTimePayingUser / 100, 2),
            'averageRevenuePerUser' => number_format($averageRevenuePerAllTimeUser / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilFourMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilFourMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilThreeMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilThreeMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilTwoMonthsAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilTwoMonthsAgo / 100, 2),
            'averageLifetimeValuePerUserRegisteredUntilOneMonthAgo' => number_format($averageLifetimeValuePerUserRegisteredUntilOneMonthAgo / 100, 2),
        ];
    }

    public function getGoogleAdsLvuStatistics()
    {
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $tenDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(10)->setTimezone('UTC');
        $twentyDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(20)->setTimezone('UTC');
        $oneMonthAgo = Carbon::now('Europe/Amsterdam')->subMonths(1)->setTimezone('UTC');
        $oneAndAHalfMonthAgo = Carbon::now('Europe/Amsterdam')->subDays(45)->setTimezone('UTC');
        $twoMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(2)->setTimezone('UTC');
        $threeMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(3)->setTimezone('UTC');
        $fourMonthsAgo = Carbon::now('Europe/Amsterdam')->subMonths(4)->setTimezone('UTC');

        $allTimePayingUsersCount = $this->payingUsersCreatedUntilDateCount($endOfToday, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilFourMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($fourMonthsAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilThreeMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($threeMonthsAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilTwoMonthsAgoCount = $this->payingUsersCreatedUntilDateCount($twoMonthsAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilOneMonthAndAHalfAgoCount = $this->payingUsersCreatedUntilDateCount($oneAndAHalfMonthAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilOneMonthAgoCount = $this->payingUsersCreatedUntilDateCount($oneMonthAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilTwentyDaysAgoCount = $this->payingUsersCreatedUntilDateCount($twentyDaysAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);
        $payingUsersUntilTenDaysAgoCount = $this->payingUsersCreatedUntilDateCount($tenDaysAgo, UserAffiliateTracking::AFFILIATE_GOOGLE);

        $allUsersCount = User::whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('affiliateTracking', function ($query) {
                $query->where('affiliate', UserAffiliateTracking::AFFILIATE_GOOGLE);
            })
            ->count();

        $allTimeRevenue = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            null,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilFourMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $fourMonthsAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilThreeMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilTwoMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilOneMonthAndAHalfAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $oneAndAHalfMonthAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilOneMonthAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilTwentyDaysAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $twentyDaysAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $revenueFromUsersUntilTenDaysAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $tenDaysAgo,
            UserAffiliateTracking::AFFILIATE_GOOGLE
        );

        $alvPerUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allUsersCount);
        $alvPerPayingUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allTimePayingUsersCount);
        $alvPerPayingUserRegisteredUntilFourMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilFourMonthsAgo, $payingUsersUntilFourMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilThreeMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilThreeMonthsAgo, $payingUsersUntilThreeMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilTwoMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwoMonthsAgo, $payingUsersUntilTwoMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAndAHalfAgo, $payingUsersUntilOneMonthAndAHalfAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAgo, $payingUsersUntilOneMonthAgoCount);
        $alvPerPayingUserRegisteredUntilTwentyDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwentyDaysAgo, $payingUsersUntilTwentyDaysAgoCount);
        $alvPerPayingUserRegisteredUntilTenDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTenDaysAgo, $payingUsersUntilTenDaysAgoCount);

        $peasantsWithCreditpack = $this->peasantsWithCreditpack(UserAffiliateTracking::AFFILIATE_GOOGLE);

        return [
            'no_credits' => $this->peasantsWithNoCreditpackCount(UserAffiliateTracking::AFFILIATE_GOOGLE),
            'never_bought' => $this->peasantsThatNeverHadCreditpackCount(UserAffiliateTracking::AFFILIATE_GOOGLE),
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
            'payingUsersRegisteredUntilFourMonthsAgoCount' => $payingUsersUntilFourMonthsAgoCount,
            'payingUsersRegisteredUntilThreeMonthsAgoCount' => $payingUsersUntilThreeMonthsAgoCount,
            'payingUsersRegisteredUntilTwoMonthsAgoCount' => $payingUsersUntilTwoMonthsAgoCount,
            'payingUsersRegisteredUntilOneMonthAndAHalfAgoCount' => $payingUsersUntilOneMonthAndAHalfAgoCount,
            'payingUsersRegisteredUntilOneMonthAgoCount' => $payingUsersUntilOneMonthAgoCount,
            'payingUsersRegisteredUntilTwentyDaysAgoCount' => $payingUsersUntilTwentyDaysAgoCount,
            'payingUsersRegisteredUntilTenDaysAgoCount' => $payingUsersUntilTenDaysAgoCount,
            'alvPerPayingUserRegistered' => number_format($alvPerPayingUserRegisteredAllTime / 100, 2),
            'alvPerUserRegistered' => number_format($alvPerUserRegisteredAllTime / 100, 2),
            'alvPerUserRegisteredUntilFourMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilFourMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilThreeMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilThreeMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilTwoMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilTwoMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAndAHalfAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAgo / 100, 2),
            'alvPerUserRegisteredUntilTwentyDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTwentyDaysAgo / 100, 2),
            'alvPerUserRegisteredUntilTenDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTenDaysAgo / 100, 2),
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

        $allTimePayingUsersCount = $this->payingUsersCreatedUntilDateCount(
            $endOfToday,
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
            Carbon::now()->subYears(10),
            $endOfToday,
            null,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilFourMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $fourMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilThreeMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilTwoMonthsAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilOneMonthAndAHalfAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $oneAndAHalfMonthAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilOneMonthAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $threeMonthsAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilTwentyDaysAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $twentyDaysAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $revenueFromUsersUntilTenDaysAgo = $this->revenueBetween(
            Carbon::now()->subYears(10),
            $endOfToday,
            $tenDaysAgo,
            null,
            UserAffiliateTracking::AFFILIATE_XPARTNERS
        );

        $alvPerUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allUsersCount);
        $alvPerPayingUserRegisteredAllTime = $this->calculateAverageRevenuePerUser($allTimeRevenue, $allTimePayingUsersCount);
        $alvPerPayingUserRegisteredUntilFourMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilFourMonthsAgo, $payingUsersUntilFourMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilThreeMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilThreeMonthsAgo, $payingUsersUntilThreeMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilTwoMonthsAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwoMonthsAgo, $payingUsersUntilTwoMonthsAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAndAHalfAgo, $payingUsersUntilOneMonthAndAHalfAgoCount);
        $alvPerPayingUserRegisteredUntilOneMonthAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilOneMonthAgo, $payingUsersUntilOneMonthAgoCount);
        $alvPerPayingUserRegisteredUntilTwentyDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTwentyDaysAgo, $payingUsersUntilTwentyDaysAgoCount);
        $alvPerPayingUserRegisteredUntilTenDaysAgo = $this->calculateAverageRevenuePerUser($revenueFromUsersUntilTenDaysAgo, $payingUsersUntilTenDaysAgoCount);

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
            'payingUsersRegisteredUntilFourMonthsAgoCount' => $payingUsersUntilFourMonthsAgoCount,
            'payingUsersRegisteredUntilThreeMonthsAgoCount' => $payingUsersUntilThreeMonthsAgoCount,
            'payingUsersRegisteredUntilTwoMonthsAgoCount' => $payingUsersUntilTwoMonthsAgoCount,
            'payingUsersRegisteredUntilOneMonthAndAHalfAgoCount' => $payingUsersUntilOneMonthAndAHalfAgoCount,
            'payingUsersRegisteredUntilOneMonthAgoCount' => $payingUsersUntilOneMonthAgoCount,
            'payingUsersRegisteredUntilTwentyDaysAgoCount' => $payingUsersUntilTwentyDaysAgoCount,
            'payingUsersRegisteredUntilTenDaysAgoCount' => $payingUsersUntilTenDaysAgoCount,
            'alvPerPayingUserRegistered' => number_format($alvPerPayingUserRegisteredAllTime / 100, 2),
            'alvPerUserRegistered' => number_format($alvPerUserRegisteredAllTime / 100, 2),
            'alvPerUserRegisteredUntilFourMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilFourMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilThreeMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilThreeMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilTwoMonthsAgo' => number_format($alvPerPayingUserRegisteredUntilTwoMonthsAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAndAHalfAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAndAHalfAgo / 100, 2),
            'alvPerUserRegisteredUntilOneMonthAgo' => number_format($alvPerPayingUserRegisteredUntilOneMonthAgo / 100, 2),
            'alvPerUserRegisteredUntilTwentyDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTwentyDaysAgo / 100, 2),
            'alvPerUserRegisteredUntilTenDaysAgo' => number_format($alvPerPayingUserRegisteredUntilTenDaysAgo / 100, 2),
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

    public function affiliateConversionsBetweenQueryBuilder(string $affiliate, $startDate, $endDate)
    {
        $query = User::whereDoesntHave('payments', function ($query) use ($startDate) {
            $query->where(
                'created_at',
                '<',
                $startDate
            )
                ->where('status', Payment::STATUS_COMPLETED);
        })
        ->whereHas('payments', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
                ->where('status', Payment::STATUS_COMPLETED);
        });

        if ($affiliate !== 'any') {
            $query->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        $query->distinct('id')
            ->orderBy('id', 'desc');

        return $query;
    }

    public function affiliateConversionsBetweenCount(string $affiliate, $startDate, $endDate)
    {
        return $this->affiliateConversionsBetweenQueryBuilder($affiliate, $startDate, $endDate)
            ->count('id');
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

    public function messagesSentByUserTypeBetweenQueryBuilder(int $userType, $startDate, $endDate)
    {

        return DB::table('conversation_messages as cm')
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', $userType)
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ]);
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


    public function paidMessagesSentByUserTypeCountBetween(int $userType, $startDate, $endDate, $affiliate = null)
    {
        $allMessagesCount = $this->messagesSentByUserTypeBetweenQueryBuilder($userType, $startDate, $endDate)
            ->count();

        $query = \DB::table('conversation_messages as cm')
            ->select(\DB::raw('COUNT(DISTINCT(u.id)) AS uniqueMessagers'))
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id');

        if ($affiliate) {
            $query->leftJoin('user_affiliate_tracking as uat', 'u.id', 'uat.user_id')
                ->where('uat.affiliate', $affiliate);
        }

        $unpaidMessagesInPeriod = $query
            ->where('ru.role_id', $userType)
            ->whereBetween('u.created_at', [
                $startDate,
                $endDate
            ])
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ])
            ->get()[0]
            ->uniqueMessagers;

        return $allMessagesCount - $unpaidMessagesInPeriod;
    }

    public function messagesSentByUserTypeLastHour()
    {
        $oneHourAgo = Carbon::now('Europe/Amsterdam')->subHours(1)->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesLastHour = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
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

        $messagesTodayCount = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
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

        $messagesCurrentMonth = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
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

        $messagesCurrentWeek = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
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

        $messagesCurrentYear = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
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
