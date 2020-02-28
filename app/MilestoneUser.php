<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestoneUser extends TimeZonedModel
{
    public $table = 'milestone_user';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'milestone_id'
    ];
}
