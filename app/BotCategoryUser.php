<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotCategoryUser extends Model
{
    public $table = 'bot_category_user';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bot_category_id'
    ];
}
