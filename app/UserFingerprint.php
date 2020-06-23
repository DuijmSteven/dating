<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserFingerprint extends Model
{
    public $table = 'user_fingerprints';

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
        'fingerprint',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    public function setFingerprint(string $fingerprint)
    {
        $this->fingerprint = $fingerprint;
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
