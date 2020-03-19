<?php

namespace App;

use Carbon\Carbon;
use http\Encoding\Stream\Inflate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationMessage extends TimeZonedModel
{
    use SoftDeletes;

    public $table = 'conversation_messages';

    protected $fillable = [
        'conversation_id',
        'operator_id',
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

    public function getSenderId()
    {
        return $this->sender_id;
    }

    public function setSenderId(int $senderId)
    {
        $this->sender_id = $senderId;
    }

    public function setOperatorId(int $operatorId)
    {
        $this->operator_id = $operatorId;
    }


    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    public function setRecipientId(int $recipientId)
    {
        $this->recipient_id = $recipientId;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setHasAttachment(bool $hasAttachment)
    {
        $this->has_attachment = $hasAttachment;
    }

    public function setConversationId(int $conversationId)
    {
        $this->conversation_id = $conversationId;
    }
}
