<?php

namespace App\Managers;

use App\User;
use Carbon\Carbon;

class StatisticsManager
{
    public function getRegistrationsToday() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function getRegistrationsYesterday() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereDate('created_at', Carbon::yesterday())
            ->count();
    }

    public function getRegistrationsCurrentWeek() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
    }

    public function getRegistrationsCurrentMonth() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
    }

    public function getRegistrationsPreviousMonth() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween(
                'created_at',
                [
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth(),
                ])
            ->count();
    }

    public function getRegistrationsCurrentYear() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween(
                'created_at',
                [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear(),
                ])
            ->count();
    }
}
