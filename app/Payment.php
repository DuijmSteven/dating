<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';

    public function peasant()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
