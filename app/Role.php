<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_ADMIN = 1;
    const ROLE_PEASANT = 2;
    const ROLE_BOT = 3;
    const ROLE_OPERATOR = 4;

    public $table = 'roles';

    public $timestamps = false;

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
