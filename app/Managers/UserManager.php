<?php

namespace App\Managers;

use App\Session;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kim\Activity\Activity;

class UserManager
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserManager constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Only used in development to insert rows in the sessions
     * table for an amount of users
     *
     * @param $userAmount
     * @return mixed
     */
    public function setRandomUsersOnline($userAmount)
    {
        $randomUsers = $this->user->with(['roles' => function ($query) {
            $query->where('name', 'user');
            $query->select('name', 'user_id');
        }])->orderByRaw('RAND()')->take($userAmount)->get();

        // This method is nly used in dev env so it is ok to do this
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Model::unguard();

        foreach ($randomUsers as $user) {
            Session::create([
                'id' => md5(uniqid(rand(), true)),
                'user_id' => $user->id,
                'payload' => base64_encode('test'),
                'last_activity' => time()
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $randomUsers;
    }

    /**
     * Retrieves collection of users that were online in the most recent
     * specified amount of minutes
     *
     * @param $minutes
     * @return User Collection
     */
    public function latestOnline($minutes)
    {
        $latestIds = Activity::users($minutes)->lists('user_id')->toArray();

        return User::with('meta')->whereIn('id', $latestIds)->limit(\UserConstants::MAX_AMOUNT_ONLINE_TO_SHOW)->get();
    }
}
