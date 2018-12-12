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
            ],
            'peasantMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->getMessagesSentByUserTypeCountToday('peasant'),
                'messagesSentYesterday' => $this->statisticsManager->getMessagesSentByUserTypeCountYesterday('peasant'),
                'messagesSentCurrentWeek' => $this->statisticsManager->getMessagesSentByUserTypeCountCurrentWeek('peasant'),
                'messagesSentCurrentMonth' => $this->statisticsManager->getMessagesSentByUserTypeCountCurrentMonth('peasant'),
                'messagesSentPreviousMonth' => $this->statisticsManager->getMessagesSentByUserTypeCountPreviousMonth('peasant'),
                'messagesSentCurrentYear' => $this->statisticsManager->getMessagesSentByUserTypeCountCurrentYear('peasant')
            ],
            'botMessageStatistics' => [
                'messagesSentToday' => $this->statisticsManager->getMessagesSentByUserTypeCountToday('bot'),
                'messagesSentYesterday' => $this->statisticsManager->getMessagesSentByUserTypeCountYesterday('bot'),
                'messagesSentCurrentWeek' => $this->statisticsManager->getMessagesSentByUserTypeCountCurrentWeek('bot'),
                'messagesSentCurrentMonth' => $this->statisticsManager->getMessagesSentByUserTypeCountCurrentMonth('bot'),
                'messagesSentPreviousMonth' => $this->statisticsManager->getMessagesSentByUserTypeCountPreviousMonth('bot'),
                'messagesSentCurrentYear' => $this->statisticsManager->getMessagesSentByUserTypeCountCurrentYear('bot')
            ],
            'peasantDeactivationStatistics' => [
                'deactivationsToday' => $this->statisticsManager->getPeasantDeactivationsCountToday(),
                'deactivationsYesterday' => $this->statisticsManager->getPeasantDeactivationsCountYesterday(),
                'deactivationsCurrentWeek' => $this->statisticsManager->getPeasantDeactivationsCountCurrentWeek(),
                'deactivationsCurrentMonth' => $this->statisticsManager->getPeasantDeactivationsCountCurrentMonth(),
                'deactivationsPreviousMonth' => $this->statisticsManager->getPeasantDeactivationsCountPreviousMonth(),
                'deactivationsCurrentYear' => $this->statisticsManager->getPeasantDeactivationsCountCurrentYear()
            ],
        ];

        return view('admin.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \config('app.name'),
                'headingLarge' => 'Dashboard',
                'headingSmall' => 'Site Statistics',
            ]
        ));
    }
}
