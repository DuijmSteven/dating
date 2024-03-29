<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    const ACCEPTED_WELCOME_MESSAGE = 1;
    const ACCEPTED_PROFILE_COMPLETION_MESSAGE = 2;

    public $table = 'milestones';

    public $timestamps = false;

    /**
     * Get the user that owns the milestone.
     */
    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
