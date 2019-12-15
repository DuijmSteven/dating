<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';

    protected $fillable = [
        'method', 'description', 'status', '$transactionId'
    ];

    public function peasant()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
