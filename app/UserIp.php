<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    public $table = 'user_ips';

    protected $dates = [
        'dob'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ip',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::PEASANT_RELATIONS
            ))
        )
            ->withCount(
                User::PEASANT_RELATION_COUNTS
            );
    }

    public function getIp()
    {
        return $this->fingerprint;
    }

    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId(int $userId)
    {
        $this->user_id = $userId;
    }

    public function getDevice()
    {
        return $this->device;
    }
}
