<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    public $table = 'conversation_messages';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo('App\User', 'id', 'sender_id')->with(['meta', 'roles', 'images']);
    }

    public function recipient()
    {
        return $this->belongsTo('App\User', 'id', 'recipient_id')->with(['meta', 'roles', 'images']);
    }
}
