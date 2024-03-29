<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    public $table = 'message_attachments';

    protected $fillable = [
        'message_id',
        'filename',
        'conversation_id',
    ];

    public $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return \StorageHelper::messageAttachmentUrl(
            $this->conversation_id,
            $this->filename
        );
    }

    public function conversationMessage()
    {
        return $this->belongsTo('App\ConversationMessage', 'message_id', 'id');
    }

    public function setFilename(string $filename)
    {
        $this->filename = $filename;
    }

    public function setConversationId(int $conversationId)
    {
        $this->conversation_id = $conversationId;
    }
}
