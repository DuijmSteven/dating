<?php

namespace App\Managers;

use App\ConversationMessage;
use App\Creditpack;
use App\Expense;
use App\Facades\Helpers\PaymentsHelper;
use App\Payment;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsManager
{
    public function revenueBetween($startDate, $endDate)
    {
        return Payment::whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }

    public function affiliateRevenueBetween(string $affiliate, $startDate, $endDate)
    {
        return Payment::whereHas('peasant.affiliateTracking', function ($query) use ($affiliate) {
            $query->where('affiliate', $affiliate);
        })
            ->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }

    public function affiliateExpensesBetween(int $payee, int $type, $startDate, $endDate)
    {
        return Expense::where('payee', $payee)
            ->where('type', $type)
            ->whereBetween('takes_place_at',
                [
                    $startDate,
                    $endDate
                ])
            ->sum('amount');
    }

    public function affiliateConversionsBetweenQueryBuilder(string $affiliate, $startDate, $endDate)
    {
        $query = User::whereDoesntHave('payments', function ($query) use ($startDate) {
            $query->where(
                'created_at',
                '<',
                $startDate
            )
                ->where('status', Payment::STATUS_COMPLETED);
        })
        ->whereHas('payments', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
                ->where('status', Payment::STATUS_COMPLETED);
        });

        if ($affiliate !== 'any') {
            $query->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });
        }

        $query->distinct('id')
            ->orderBy('id', 'desc');

        return $query;
    }

    public function affiliateConversionsBetweenCount(string $affiliate, $startDate, $endDate)
    {
        return $this->affiliateConversionsBetweenQueryBuilder($affiliate, $startDate, $endDate)
            ->count('id');
    }

    public function registrationsCountBetween($startDate, $endDate)
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->whereBetween('created_at',
                [
                    $startDate,
                    $endDate
                ])
            ->count();
    }

    public function messagesSentCountBetween($startDate, $endDate)
    {
        return ConversationMessage::whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->count();
    }

    public function messagesSentByUserTypeBetweenQueryBuilder(int $userType, $startDate, $endDate)
    {

        return DB::table('conversation_messages as cm')
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', $userType)
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ]);
    }

    public function publicChatMessagesSentByUserTypeBetweenQueryBuilder(int $userType, $startDate, $endDate)
    {

        return DB::table('public_chat_items as pci')
            ->leftJoin('users as u', 'u.id', 'pci.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', $userType)
            ->whereBetween('pci.created_at', [
                $startDate,
                $endDate
            ]);
    }

    public function messagesSentByUserTypeBetween(int $userType, $startDate, $endDate)
    {
        return ConversationMessage::whereHas('sender.roles', function ($query) use ($userType) {
            $query->where('id', $userType);
        })->whereBetween('created_at',
            [
                $startDate,
                $endDate
            ])
            ->get();
    }

    public function messagesSentByUserTypeCountBetween(int $userType, $startDate, $endDate)
    {
        return $this->messagesSentByUserTypeBetweenQueryBuilder($userType, $startDate, $endDate)
            ->count();
    }


    public function paidMessagesSentByUserTypeCountBetween(int $userType, $startDate, $endDate)
    {
        $allMessagesCount = $this->messagesSentByUserTypeBetweenQueryBuilder($userType, $startDate, $endDate)
            ->count();

        $unpaidMessagesInPeriod = \DB::table('conversation_messages as cm')
            ->select(\DB::raw('COUNT(DISTINCT(u.id)) AS uniqueMessagers'))
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', $userType)
            ->whereBetween('u.created_at', [
                $startDate,
                $endDate
            ])
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ])
            ->get()[0]->uniqueMessagers;


        $query = DB::table('conversation_messages as cm')
            ->leftJoin('users as u', 'u.id', 'cm.sender_id')
            ->leftJoin('role_user as ru', 'ru.user_id', 'u.id')
            ->where('ru.role_id', $userType)
            ->whereBetween('cm.created_at', [
                $startDate,
                $endDate
            ]);


        return $allMessagesCount - $unpaidMessagesInPeriod;
    }

    public function messagesSentByUserTypeLastHour()
    {
        $oneHourAgo = Carbon::now('Europe/Amsterdam')->subHours(1)->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesLastHour = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
            $oneHourAgo,
            $now
        );

        if ($messagesLastHour === 0) {
            return 'No messages';
        }

        return $messagesLastHour;
    }

    public function messagesSentByUserTypePerHourToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesTodayCount = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
            $startOfToday,
            $now
        );

        if ($messagesTodayCount === 0) {
            return 'No messages';
        }

        if ($now->diffInMinutes($startOfToday) === 0) {
            return 'Not available until 00:01am';
        }

        return number_format(($messagesTodayCount / $now->diffInMinutes($startOfToday)) * 60, 0);
    }

    public function messagesSentByUserTypePerHourCurrentMonth()
    {
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        $messagesCurrentMonth = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
            $startOfMonth,
            $endOfMonth
        );

        if ($messagesCurrentMonth === 0) {
            return 'No messages';
        }

        if (Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfMonth) === 0) {
            return 'Not available until 1am';
        }

        return number_format($messagesCurrentMonth / Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfMonth), 0);
    }

    public function messagesSentByUserTypePerHourCurrentWeek()
    {
        $startOfWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        $messagesCurrentWeek = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
            $startOfWeek,
            $endOfWeek
        );

        if ($messagesCurrentWeek === 0) {
            return 'No messages';
        }

        if (Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfWeek) === 0) {
            return 'Not available until 1am';
        }

        return number_format($messagesCurrentWeek / Carbon::now('Europe/Amsterdam')->setTimezone('UTC')->diffInHours($startOfWeek), 0);
    }

    public function messagesSentByUserTypePerHourCurrentYear()
    {
        $startOfYear = Carbon::now('Europe/Amsterdam')->startOfYear()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $now = Carbon::now('Europe/Amsterdam')->setTimezone('UTC');

        $messagesCurrentYear = $this->messagesSentByUserTypeCountBetween(
            User::TYPE_PEASANT,
            $startOfYear,
            $endOfToday
        );

        if ($messagesCurrentYear === 0) {
            return 'No messages';
        }

        if ($now->diffInHours($startOfYear) === 0) {
            return 'Not available until 1am';
        }

        return number_format($messagesCurrentYear / $now->diffInHours($startOfYear), 0);
    }

    public function publicChatMessagesSentByUserTypeCountBetween(int $userType, $startDate, $endDate)
    {
        return $this->publicChatMessagesSentByUserTypeBetweenQueryBuilder($userType, $startDate, $endDate)
            ->count();
    }

    public function peasantDeactivationsCountBetween($startDate, $endDate)
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })->whereBetween('deactivated_at',
            [
                $startDate,
                $endDate
            ])
            ->where('deactivated_at', '!=', null)
            ->count();
    }

    public function peasantsWithNoCreditpackQueryBuilder()
    {
        return User::where('active', true)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })->whereHas('account', function ($query) {
                $query->where('credits', 0);
            });
    }

    public function peasantsWithNoCreditpack()
    {
        return $this->peasantsWithNoCreditpackQueryBuilder()->get();
    }

    public function peasantsWithNoCreditpackCount()
    {
        return $this->peasantsWithNoCreditpackQueryBuilder()->count();
    }

    public function peasantsThatNeverHadCreditpackQueryBuilder()
    {
        return User::where('active', true)
            ->has('payments', '=', 0)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->orWhereHas('payments', function ($query) {
                $query->whereNull('creditpack_id');
            })
            ->orderBy('created_at', 'desc');
    }

    public function peasantsThatNeverHadCreditpack()
    {
        return $this->peasantsThatNeverHadCreditpackQueryBuilder()->get();
    }

    public function peasantsThatNeverHadCreditpackCount()
    {
        return $this->peasantsThatNeverHadCreditpackQueryBuilder()->count();
    }

    public function topMessagersBetweenDates($startDate, $endDate, int $amount)
    {
        return User::with(['profileImage', 'account', 'messages' => function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('payments', function ($query) {
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

    public function peasantMessagersOnARoll($startDate, $endDate, int $amount, $countLimit = 1)
    {
        return User::with(['account', 'messages' => function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->whereHas('messages', function ($query) use ($startDate, $endDate) {
                $query->where('created_at', '>=', $startDate);
                $query->where('created_at', '<=', $endDate);
            })
            ->get()
            ->filter(function ($user) use ($countLimit) {
                return $user->messages->count() >= $countLimit;
            })
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
            ->whereHas('roles', function ($query) {
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

    public function peasantsWithCreditpackQueryBuilder()
    {
        return User::where('active', true)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })->whereHas('account', function ($query) {
                $query->where('credits', '>', 0);
            })->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'desc')->take(1);
            })
            ->orderBy('created_at', 'desc');
    }

    public function peasantsWithCreditpack()
    {
        return $this->peasantsWithCreditpackQueryBuilder()->get();
    }

    public function peasantsWithCreditpackCount()
    {
        return $this->peasantsWithCreditpackQueryBuilder()->count();
    }

    /**
     * @param Collection $peasants
     * @param int $creditpackId
     * @return Collection
     */
    public function filterPeasantsWithCreditpackId(Collection $peasants, int $creditpackId)
    {
        return $peasants->filter(function ($user) use ($creditpackId) {
            return $user->payments[count($user->payments) - 1]->getCreditpackId() == $creditpackId;
        });
    }

    public function filterPeasantsWithCreditpackIdCount(Collection $peasants, int $creditpackId)
    {
        return $this->filterPeasantsWithCreditpackId($peasants, $creditpackId)->count();
    }
}
