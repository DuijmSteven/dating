<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Managers\StatisticsManager;
use App\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
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
            'registrationsToday' => $this->statisticsManager->getRegistrationsToday(),
            'registrationsYesterday' => $this->statisticsManager->getRegistrationsYesterday(),
            'registrationsCurrentWeek' => $this->statisticsManager->getRegistrationsCurrentWeek(),
            'registrationsCurrentMonth' => $this->statisticsManager->getRegistrationsCurrentMonth(),
            'registrationsPreviousMonth' => $this->statisticsManager->getRegistrationsPreviousMonth(),
            'registrationsCurrentYear' => $this->statisticsManager->getRegistrationsCurrentYear()
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
