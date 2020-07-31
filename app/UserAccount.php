<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    public $table = 'user_accounts';

    protected $fillable = [
        'user_id',
        'credits'
    ];

    public function peasant()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getCredits()
    {
        return $this->credits;
    }

    public function setCredits(int $credits)
    {
        $this->credits = $credits;
    }
}
