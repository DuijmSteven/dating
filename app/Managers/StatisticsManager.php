<?php

namespace App\Managers;

use App\ConversationMessage;
use App\Creditpack;
use App\Facades\Helpers\PaymentsHelper;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
        return User::where('active', true)
        ->whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })->whereHas('account', function($query) {
            $query->where('credits', 0);
        })
        ->count();
    }

    public function peasantsThatNeverHadCreditpack() : int {
        return User::where('active', true)
        ->has('payments', '=', 0)
        ->whereHas('roles', function($query) {
            $query->where('name', 'peasant');
        })
        ->orWhereHas('payments', function ($query) {
            $query->whereNull('creditpack_id');
        })
        ->count();
    }

    public function topMessagersBetweenDates($startDate, $endDate, int $amount)
    {
        return User::with(['profileImage', 'account', 'messages' => function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            }])
            ->whereHas('roles', function($query) {
                $query->where('id', User::TYPE_PEASANT);
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
            ->take($amount);
    }

    public function topOperatorMessagersBetweenDates($startDate, $endDate, int $amount)
    {
        return User::with(['messagesAsOperator' => function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }])
            ->whereHas('roles', function($query) {
                $query->whereIn('id', [User::TYPE_OPERATOR, User::TYPE_ADMIN]);
            })
            ->whereHas('messagesAsOperator', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            })
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messagesAsOperator->count();
            })
            ->take($amount);
    }

    public function peasantsWithCreditpack(): Collection {
        return User::where('active', true)
            ->whereHas('roles', function($query) {
                $query->where('name', 'peasant');
            })->whereHas('account', function($query) {
                $query->where('credits', '>', 0);
            })->whereHas('payments', function($query) {
                $query->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'desc')->take(1);
            })->get();
    }

    public function filterPeasantsWithCreditpackId(Collection $peasants, int $creditpackId) : int {
        return $peasants->filter(function ($user) use ($creditpackId) {
            return $user->payments[count($user->payments) - 1]->getCreditpackId() == $creditpackId;
        })->count();
    }
}
