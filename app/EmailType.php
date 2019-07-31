<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailType extends Model
{
    public $table = 'email_types';


    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
