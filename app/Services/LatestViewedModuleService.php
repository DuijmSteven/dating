<?php

namespace App\Services;

use App\User;
use App\UserView;
use Illuminate\Support\Facades\DB;

class LatestViewedModuleService
{
    public static function latestUsersViewed(int $userId, int $limit = 20)
    {
        return UserView::with(['viewed', 'viewed.profileImage', 'viewed.meta'])
            ->whereHas('viewed')
            ->select(DB::raw('*, max(created_at) as created_at'))
            ->where('viewer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->groupBy(['viewed_id'])
            ->take($limit)
            ->get();
    }

    public static function latestUsersThatHaveViewed(int $userId, int $limit = 20)
    {
        return UserView::with(['viewer', 'viewed.profileImage', 'viewer.meta'])
            ->whereHas('viewer.roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
                $query->orWhere('id', User::TYPE_BOT);
            })
            ->select(DB::raw('*, max(created_at) as created_at'))
            ->where('viewed_id', $userId)
            ->orderBy('created_at', 'desc')
            ->groupBy(['viewer_id'])
            ->take($limit)
            ->get();
    }
}
