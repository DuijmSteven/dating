<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Managers\StatisticsManager;
use App\User;
use Carbon\Carbon;

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
        $viewData = [
            'registrationStatistics' => [
                'registrationsToday' => $this->statisticsManager->getRegistrationsCountToday(),
                'registrationsYesterday' => $this->statisticsManager->getRegistrationsCountYesterday(),
                'registrationsCurrentWeek' => $this->statisticsManager->getRegistrationsCountCurrentWeek(),
                'registrationsCurrentMonth' => $this->statisticsManager->getRegistrationsCountCurrentMonth(),
                'registrationsPreviousMonth' => $this->statisticsManager->getRegistrationsCountPreviousMonth(),
                'registrationsCurrentYear' => $this->statisticsManager->getRegistrationsCountCurrentYear()
            ],
            'messageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->getMessagesSentCountToday(),
                'messagesSentYesterday' => $this->statisticsManager->getMessagesSentCountYesterday(),
                'messagesSentCurrentWeek' => $this->statisticsManager->getMessagesSentCountCurrentWeek(),
                'messagesSentCurrentMonth' => $this->statisticsManager->getMessagesSentCountCurrentMonth(),
                'messagesSentPreviousMonth' => $this->statisticsManager->getMessagesSentCountPreviousMonth(),
                'messagesSentCurrentYear' => $this->statisticsManager->getMessagesSentCountCurrentYear()
            ]
        ];

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \config('app.name'),
                'headingLarge' => 'Dashboard',
            ]
        ));
    }
}
