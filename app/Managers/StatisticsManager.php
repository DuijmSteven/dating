<?php

namespace App\Managers;

use App\ConversationMessage;
use App\User;
use Carbon\Carbon;

class StatisticsManager
{
    public function registrationsCountBetween($startDate, $endDate) {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
                ->count();
    }

    public function registrationsCountOnDay($date) : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereDate('created_at', $date)
            ->count();
    }

    public function messagesSentCountBetween($startDate, $endDate) {
        return ConversationMessage::whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
                ->count();
    }

    public function messagesSentCountOnDay($date) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) {
            $query->where('name', 'peasant')
                ->orWhere('name', 'bot');
        })->whereDate('created_at', $date)
            ->count();
    }

    public function messagesSentByUserTypeCountOnDay(string $userType, $date) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereDate('created_at', $date)
            ->count();
    }

    public function messagesSentByUserTypeCountBetween(string $userType, $startDate, $endDate) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->count();
    }

    public function peasantDeactivationsCountOnDay($date) : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereDate('deactivated_at', $date)
            ->count();
    }

    public function peasantDeactivationsCountYesterday() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereDate('deactivated_at', Carbon::yesterday())
            ->count();
    }

    public function peasantDeactivationsCountBetween($startDate, $endDate) : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereBetween('deactivated_at',
            [
                $startDate,
                $endDate
            ])
            ->count();
    }
}
