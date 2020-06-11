<?php

namespace App\Console\Commands;

use App\Managers\AffiliateManager;
use App\User;
use App\UserAffiliateTracking;
use Illuminate\Console\Command;

class ValidateEligibleXpartnersLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xpartners:validate-eligible-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validates eligible xparters leads';
    /**
     * @var AffiliateManager
     */
    private AffiliateManager $affiliateManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        AffiliateManager $affiliateManager
    )
    {
        parent::__construct();
        $this->affiliateManager = $affiliateManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $eligibleLeads = User::with([
            'affiliateTracking',
            'meta'
        ])
            ->whereHas('affiliateTracking', function ($query) {
                $query->where('affiliate', UserAffiliateTracking::AFFILIATE_XPARTNERS)
                    ->where('lead_eligibility', UserAffiliateTracking::LEAD_ELIGIBILITY_ELIGIBLE)
                    ->where('lead_status', UserAffiliateTracking::LEAD_STATUS_UNVALIDATED);
            })
            ->get();

        \Log::debug($eligibleLeads->count());

        foreach ($eligibleLeads as $lead) {
            try {
                $this->affiliateManager->validateXpartnersLead($lead);
            } catch (\Exception $exception) {
                continue;
            }
        }
    }
}
