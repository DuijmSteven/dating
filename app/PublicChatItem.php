<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicChatItem extends TimeZonedModel
{
    const TYPE_AUTOMATED = 1;
    const TYPE_PEASANT = 2;
    const TYPE_OPERATOR = 3;
    const TYPE_ADMIN = 4;

    use SoftDeletes;

    public $table = 'public_chat_items';

    protected $fillable = [
        'sender_id',
        'operator_id',
        'type',
        'body',
        'published_at'
    ];

    public $dates = [
        'published_at'
    ];

    public static function typeDescriptionPerId()
    {
        return [
            self::TYPE_AUTOMATED => 'Automated',
            self::TYPE_PEASANT => 'Peasant',
            self::TYPE_OPERATOR => 'Operator',
            self::TYPE_ADMIN => 'Admin',
        ];
    }

    protected $appends = ['publishedAtHumanReadable'];

    public function getPublishedAtHumanReadableAttribute()
    {
        return $this->published_at->diffForHumans();
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

    public function getOperatorId()
    {
        return $this->operator_id;
    }

    public function setOperatorId(int $operatorId)
    {
        $this->operator_id = $operatorId;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setPublishedAt(Carbon $publishedAt)
    {
        $this->published_at = $publishedAt;
    }

    /**
     * @return Carbon
     */
    public function getPublishedAt()
    {
        return $this->published_at->tz(self::TIMEZONE);
    }
}
