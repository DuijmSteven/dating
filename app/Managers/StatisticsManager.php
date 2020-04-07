<?php

namespace App\Managers;

use App\ConversationMessage;
use App\Facades\Helpers\PaymentsHelper;
use App\Payment;
use App\User;
use Carbon\Carbon;

class StatisticsManager
{
    public function revenueBetween($startDate, $endDate) {
        return Payment::whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->where('status', 3)
            ->sum('amount');
    }

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

    public function messagesSentCountBetween($startDate, $endDate) {
        return ConversationMessage::whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
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

    public function peasantsWithNoCreditpack() : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereHas('account', function($query) {
            $query->where('credits', 0);
        })
        ->count();
    }

    public function peasantsThatNeverHadCreditpack() : int {
        return User::has('payments', '=', 0)
        ->whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })
        ->orWhereHas('payments', function ($query) {
            $query->whereNull('creditpack_id');
        })
        ->count();
    }

    public function peasantsWithSmallCreditpack() : int {
        return $this->peasantsCreditpackId(1);
    }

    public function peasantsWithMediumCreditpack() : int {
        return $this->peasantsCreditpackId(2);
    }

    public function peasantsWithLargeCreditpack() : int {
        return $this->peasantsCreditpackId(3);
    }

    public function topMessagersBetweenDates($startDate, $endDate)
    {
        return User::with(['profileImage', 'account', 'messages' => function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            }])
            ->whereHas('roles', function($query) {
                $query->where('name', 'peasant');
            })
            ->whereHas('payments', function($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->whereHas('messages', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            })
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messages->count();
            })
            ->take(6);
    }

    private function peasantsCreditpackId(int $creditpackId) : int {
        return User::whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereHas('account', function($query) {
            $query->where('credits', '>', 0);
        })->whereHas('payments', function($query) {
            $query->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'desc')->take(1);
        })->get()->filter(function ($user) use ($creditpackId) {
            return $user->payments[count($user->payments) - 1]->getCreditpackId() == $creditpackId;
        })->count();
    }
}
