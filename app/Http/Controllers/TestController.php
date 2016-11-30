<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function storeUserFavorite(int $userId, int $favoriteId)
    {
        $key = 'users.favorites.' . $userId;

        return Redis::sadd($key, $favoriteId);
    }

    public function retrieveUserFavorites(int $userId)
    {
        return Redis::smembers('users.favorites.' . $userId);
    }

    public function deleteUserFavorite(int $userId, int $favoriteId)
    {
        return Redis::srem('users.favorites.' . $userId, [$favoriteId]);
    }
}
