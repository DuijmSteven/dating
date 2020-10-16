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
}
