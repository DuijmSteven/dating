<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $table = 'sessions';

    public $timestamps = false;

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->with(['meta', 'roles', 'images']);
    }
}
