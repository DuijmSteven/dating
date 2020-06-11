<?php

namespace App\Console\Commands;

use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckXpartnersLeadsWIthPendingEligibilityStatus extends Command
{
    const UNIQUE_PROFILES_VIEWED_THRESHOLD = 3;
    const MAX_DAYS_SINCE_REGISTRATION_TO_REACT = 60;
    const AMOUNT_OF_LOGINS_THRESHOLD = 3;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xpartners:check-pending-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks xparters pending leads for eligibility and changes their eligibility';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pendingLeads = User::with([
            'affiliateTracking',
            'meta',
            'hasViewed',
            'hasViewedUnique'
        ])
            ->withCount([
                'messages',
            ])
            ->whereHas('affiliateTracking', function ($query) {
               $query->where('affiliate', UserAffiliateTracking::AFFILIATE_XPARTNERS)
                   ->where('lead_eligibility', UserAffiliateTracking::LEAD_ELIGIBILITY_PENDING)
                   ->where('lead_status', UserAffiliateTracking::LEAD_STATUS_UNVALIDATED);
            })
            ->get();

        foreach ($pendingLeads as $lead) {
            $hasSpentMoney = $lead->completedPayments->count() > 0;

            if ($hasSpentMoney) {
                $this->setLeadEligibility(
                    $lead,
                    UserAffiliateTracking::LEAD_ELIGIBILITY_ELIGIBLE
                );

                continue;
            }

            $hasDeactivated = null === $lead->getDeactivatedAt() ? false : true;
            $hasSentFreeCredit = $lead->messages_count > 0;
            $amountOfProfilesViewed = $lead->hasViewed->count();
            $amountOfUniqueProfilesViewed = $lead->hasViewedUnique->count();
            $daysSinceRegistration = $lead->getCreatedAt()->diffInDays(Carbon::now());
            $hoursSinceRegistration = $lead->getCreatedAt()->diffInHours(Carbon::now());
            $amountOfLogins = $lead->meta->getLoginsCount();
            $leadIsDutch = $lead->affiliateTracking->getCountryCode() === 'nl';

            if ($hasDeactivated) {
                $this->setLeadEligibility(
                    $lead,
                    UserAffiliateTracking::LEAD_ELIGIBILITY_INELIGIBLE
                );
                continue;
            }

            if (
                !$leadIsDutch &&
                $daysSinceRegistration > 5
            ) {
                $this->setLeadEligibility(
                    $lead,
                    UserAffiliateTracking::LEAD_ELIGIBILITY_INELIGIBLE
                );
                continue;
            }

            if (
                $hasSentFreeCredit &&
                $amountOfUniqueProfilesViewed > self::UNIQUE_PROFILES_VIEWED_THRESHOLD &&
                $amountOfLogins > self::AMOUNT_OF_LOGINS_THRESHOLD &&
                $leadIsDutch &&
                $daysSinceRegistration > 1
            ) {
                $this->setLeadEligibility(
                    $lead,
                    UserAffiliateTracking::LEAD_ELIGIBILITY_ELIGIBLE
                );
                continue;
            }

            if ($daysSinceRegistration > self::MAX_DAYS_SINCE_REGISTRATION_TO_REACT) {
                $this->setLeadEligibility(
                    $lead,
                    UserAffiliateTracking::LEAD_ELIGIBILITY_INELIGIBLE
                );
                continue;
            }

        }

    }

    private function setLeadEligibility(User $lead, int $eligibility)
    {
        $lead->affiliateTracking->setLeadEligibility(
            $eligibility
        );

        $lead->affiliateTracking->save();
    }
}
