<?php

namespace App\Http\Controllers\Api;

use App\Managers\StatisticsManager;
use Carbon\Carbon;

class FinancialController
{
    const SALES_TAX = 0.21;

    /**
     * @var StatisticsManager
     */
    private StatisticsManager $statisticsManager;

    public function __construct(
        StatisticsManager $statisticsManager
    ) {
        $this->statisticsManager = $statisticsManager;
    }

    public function getFinancialData()
    {
        try {
            $startOfQuarter = Carbon::now()->startOfQuarter()->setTimezone('UTC');
            $endOfQuarter = Carbon::now()->endOfQuarter()->setTimezone('UTC');
            $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

            $revenueInQuarter = $this->statisticsManager->revenueBetween(
                $startOfQuarter,
                $endOfToday
            );

            $taxInQuarter = $revenueInQuarter - ($revenueInQuarter/(1 + self::SALES_TAX));

            $data = [
                'currentQuarter' => [
                    'startDate' => $startOfQuarter->format('d-M-Y'),
                    'endDate' => $endOfQuarter->format('d-M-Y'),
                    'revenue' => $revenueInQuarter,
                    'tax' => $taxInQuarter
                ]
            ];

            return response()->json($data);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
}
