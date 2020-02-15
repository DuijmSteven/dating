<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestoneUser extends Model
{
    public $table = 'milestone_user';

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
