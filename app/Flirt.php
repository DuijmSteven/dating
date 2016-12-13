<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flirt extends Model
{
    public $timestamps = false;

    protected $fillable = ['sender_id', 'recipient_id', 'seen'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sender()
    {
        return $this->hasOne('App\User', 'id', 'sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recipient()
    {
        return $this->hasOne('App\User', 'id', 'recipient_id');
    }
}
