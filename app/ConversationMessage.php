<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    public $table = 'conversation_messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'recipient_id',
        'body',
        'has_attachment',
        'type'
    ];

    public static $allowedMessageTypes = [
        'generic',
        'flirt'
    ];

    protected $appends = ['createdAtHumanReadable'];

    public function getCreatedAtHumanReadableAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->getCreatedAt()))->diffForHumans();
    }

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
        return $this->belongsTo('App\User', 'sender_id', 'id')->with(['meta', 'roles', 'images']);
    }

    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id', 'id')->with(['meta', 'roles', 'images']);
    }

    public function attachment()
    {
        return $this->hasOne('App\MessageAttachment', 'message_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany('App\ConversationNote', 'conversation_id', 'id');
    }

    public function getConversationId() {
        return $this->conversation_id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
}
