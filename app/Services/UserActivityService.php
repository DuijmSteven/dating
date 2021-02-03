<?php

namespace App\Services;

use App\User;
use Carbon\Carbon;

/**
 * Class UserActivityService
 * @package App\Services
 */
class UserActivityService
{
    const GENERAL_ONLINE_TIMEFRAME_IN_MINUTES = 11;
    const PEASANT_MAILING_ONLINE_TIMEFRAME_IN_MINUTES = 5;

    /**
     * @param int $sinceMinutesAgo
     * @return mixed
     */
    public function getOnlineUsers($sinceMinutesAgo = self::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES)
    {
        return User
            ::where(
                'last_online_at',
                '>=',
                Carbon::now('Europe/Amsterdam')->subMinutes($sinceMinutesAgo)->setTimezone('UTC')
            )
            ->where('last_online_at', '!=', null)
            ->where('active', true)
            ->get();
    }

    /**
     * @param int $sinceMinutesAgo
     * @return mixed
     */
    public function getOnlineUserIds($sinceMinutesAgo = self::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES)
    {
        return $this->getOnlineUsers()
            ->pluck('id')
            ->toArray();
    }

    public function getOnlineCountByType(
        array $onlineIds,
        int $roleId = null,
        int $genderId = null,
        int $lookingForGenderId = null
    ) {
        return User::with('roles')
            ->whereHas('roles', function ($query) use ($roleId) {
                if ($roleId) {
                    $query->where('id', $roleId);
                }
            })
            ->whereHas('meta', function ($query) use ($genderId, $lookingForGenderId) {
                if ($genderId) {
                    $query->where('gender', $genderId);
                }

                if ($lookingForGenderId) {
                    $query->where('looking_for_gender', $lookingForGenderId);
                }
            })
            ->whereIn('id', $onlineIds)
            ->count();
    }

    public function getActiveCountByType(
        bool $active = null,
        int $roleId = null,
        int $genderId = null,
        int $lookingForGenderId = null
    ) {
        $query = User::with('roles')
            ->whereHas('roles', function ($query) use ($roleId) {
                $query->where('id', $roleId);
            })
            ->whereHas('meta', function ($query) use ($genderId, $lookingForGenderId) {
                $query->where('gender', $genderId);
                $query->where('looking_for_gender', $lookingForGenderId);
            });

        if (is_bool($active)) {
            $query->where('active', $active);
        }

        return $query->count();
    }
}
