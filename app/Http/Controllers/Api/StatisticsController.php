<?php

namespace App\Http\Controllers\Api;

use App\Expense;
use App\Http\Controllers\Controller;
use App\Managers\ChartsManager;
use App\Managers\StatisticsManager;
use App\Payment;
use App\Role;
use App\Services\UserActivityService;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController
{
    const SALES_TAX = 0.21;

    /** @var StatisticsManager */
    private $statisticsManager;

    public function __construct(
        StatisticsManager $statisticsManager
    ) {
        $this->statisticsManager = $statisticsManager;
    }
}
