<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTypeUser extends Model
{
    public $table = 'email_type_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email_type_id',
        'active'
    ];
}
