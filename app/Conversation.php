<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public $table = 'conversations';

    /**
     * Get all the messages of a conversation
     */
    public function messages()
    {
        return $this->hasMany('App\ConversationMessage');
    }
}
