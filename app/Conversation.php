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
        return $this->hasMany('App\ConversationMessage')->orderBy('created_at', 'asc');
    }

    public function userA()
    {
        return $this->belongsTo('App\User', 'user_a_id', 'id')->with(['meta', 'roles', 'images']);
    }

    public function userB()
    {
        return $this->belongsTo('App\User', 'user_b_id', 'id')->with(['meta', 'roles', 'images']);
    }
}
