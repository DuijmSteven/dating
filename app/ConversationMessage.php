<?php

namespace App;

use Carbon\Carbon;
use http\Encoding\Stream\Inflate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationMessage extends TimeZonedModel
{
    use SoftDeletes;

    public $timestamps = true;

    const OPERATOR_MESSAGE_TYPE_STOPPED = 1;

    public $table = 'conversation_messages';

    protected $fillable = [
        'conversation_id',
        'operator_id',
        'operator_message_type',
        'sender_id',
        'recipient_id',
        'body',
        'has_attachment',
        'type',
        'paid'
    ];

    public static $allowedMessageTypes = [
        'generic',
        'flirt'
    ];

    public $dates = [
        'deleted_at'
    ];


    protected $appends = [
        'createdAtHumanReadable',
        'attachmentUrl'
    ];

    public function getCreatedAtHumanReadableAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->getCreatedAt()))->diffForHumans();
    }

    public function getAttachmentUrlAttribute()
    {
        $url = null;

        if ($this->attachment) {
            $url = \StorageHelper::messageAttachmentUrl(
                $this->conversation_id,
                $this->attachment->filename
            );
        }

        return $url;
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

    public function operator()
    {
        return $this->belongsTo('App\User', 'operator_id', 'id');
    }

    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id', 'id')->with(['meta', 'roles', 'images']);
    }

    public function attachment()
    {
        return $this->hasOne(MessageAttachment::class, 'message_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(ConversationNote::class, 'conversation_id', 'id');
    }

    public function getConversationId() {
        return $this->conversation_id;
    }

    public function getId()
    {
        return $this->id;
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

    public function setOperatorMessageType(?int $operatorMessageType)
    {
        $this->operator_message_type = $operatorMessageType;
    }

    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    public function setRecipientId(int $recipientId)
    {
        $this->recipient_id = $recipientId;
    }

    public function getBody()
    {
        return $this->body;
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

    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }
}
