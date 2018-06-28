<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotCategory extends Model
{
    public $table = 'bot_categories';

    public $timestamps = false;

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsToMany('App\User');
    }
}
