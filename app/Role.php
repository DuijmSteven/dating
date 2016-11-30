<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
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
