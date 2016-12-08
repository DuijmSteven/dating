<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public $table = 'conversations';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\ConversationMessage');
    }

    public function userA()
    {
        return $this->belongsTo('App\User', 'id', 'user_a_id')->with(['meta', 'roles', 'images']);
    }

    public function userB()
    {
        return $this->belongsTo('App\User', 'id', 'user_a_id')->with(['meta', 'roles', 'images']);
    }
}
