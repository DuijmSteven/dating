<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redis;

class FavoriteController extends Controller
{
    /**
     * @param int $userId
     * @param int $favoriteId
     * @return mixed
     */
    public function storeUserFavorite(int $userId, int $favoriteId)
    {
        $key = 'users.favorites.' . $userId;

        return Redis::sadd($key, $favoriteId);
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function retrieveUserFavorites(int $userId)
    {
        return Redis::smembers('users.favorites.' . $userId);
    }

    /**
     * @param int $userId
     * @param int $favoriteId
     * @return mixed
     */
    public function deleteUserFavorite(int $userId, int $favoriteId)
    {
        return Redis::srem('users.favorites.' . $userId, [$favoriteId]);
    }
}
