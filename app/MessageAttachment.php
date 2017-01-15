<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    public $table = 'message_attachments';

    protected $fillable = [
        'message_id',
        'filename'
    ];

    public function conversationMessage()
    {
        return $this->belongsTo('App\ConversationMessage', 'message_id', 'id');
    }
}
