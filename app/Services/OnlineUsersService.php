<?php

namespace App\Services;

use Kim\Activity\Activity;

/**
 * Class OnlineUsersService
 * @package App\Services
 */
class OnlineUsersService
{
    const CONSIDERED_ONLINE_FOR_MINUTES_AFTER_LAST_ACTIVITY = 10;

    public function getOnlineUserIds(
        int $minutesSinceLasActivity = self::CONSIDERED_ONLINE_FOR_MINUTES_AFTER_LAST_ACTIVITY
    ) {
        return Activity::users($minutesSinceLasActivity)->pluck('user_id')->toArray();
    }
}
