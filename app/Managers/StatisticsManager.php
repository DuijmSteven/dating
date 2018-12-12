<?php

namespace App\Managers;

use App\ConversationMessage;
use App\User;
use Carbon\Carbon;

class StatisticsManager
{
    public function getRegistrationsCountToday() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function getRegistrationsCountYesterday() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereDate('created_at', Carbon::yesterday())
            ->count();
    }

    public function getRegistrationsCountCurrentWeek() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
    }

    public function getRegistrationsCountCurrentMonth() : int {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
    }

    public function getRegistrationsCountPreviousMonth() : int {
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

    public function getRegistrationsCountCurrentYear() : int {
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

    public function getMessagesSentCountToday() : int {
        return ConversationMessage::whereDate('created_at', Carbon::today())
            ->count();
    }

    public function getMessagesSentCountYesterday() : int {
        return ConversationMessage::whereDate('created_at', Carbon::yesterday())
            ->count();
    }

    public function getMessagesSentCountCurrentWeek() : int {
        return ConversationMessage::whereBetween('created_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();
    }

    public function getMessagesSentCountCurrentMonth() : int {
        return ConversationMessage::whereBetween('created_at',
            [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->count();
    }

    public function getMessagesSentCountPreviousMonth() : int {
        return ConversationMessage::whereBetween(
                'created_at',
                [
                    Carbon::now()->startOfMonth()->subMonth(),
                    Carbon::now()->subMonth()->endOfMonth(),
                ])
            ->count();
    }

    public function getMessagesSentCountCurrentYear() : int {
        return ConversationMessage::whereBetween(
                'created_at',
                [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear(),
                ])
            ->count();
    }

    public function getMessagesSentByUserTypeCountToday(string $userType) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function getMessagesSentByUserTypeCountYesterday(string $userType) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereDate('created_at', Carbon::yesterday())
            ->count();
    }

    public function getMessagesSentByUserTypeCountCurrentWeek(string $userType) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
    }

    public function getMessagesSentByUserTypeCountCurrentMonth(string $userType) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
    }

    public function getMessagesSentByUserTypeCountPreviousMonth(string $userType) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereBetween(
            'created_at',
            [
                Carbon::now()->startOfMonth()->subMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->count();
    }

    public function getMessagesSentByUserTypeCountCurrentYear(string $userType) : int {
        return ConversationMessage::whereHas('sender.roles', function($query) use ($userType) {
            $query->where('name', $userType);
        })->whereBetween(
            'created_at',
            [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->count();
    }

    public function getPeasantDeactivationsCountToday() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereDate('deactivated_at', Carbon::today())
            ->count();
    }

    public function getPeasantDeactivationsCountYesterday() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereDate('deactivated_at', Carbon::yesterday())
            ->count();
    }

    public function getPeasantDeactivationsCountCurrentWeek() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereBetween('deactivated_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();
    }

    public function getPeasantDeactivationsCountCurrentMonth() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereBetween('deactivated_at',
            [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->count();
    }

    public function getPeasantDeactivationsCountPreviousMonth() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereBetween(
            'deactivated_at',
            [
                Carbon::now()->startOfMonth()->subMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->count();
    }

    public function getPeasantDeactivationsCountCurrentYear() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereBetween(
            'deactivated_at',
            [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->count();
    }
}
