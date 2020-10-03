<?php

namespace App;

class BotMessage extends TimeZonedModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const USAGE_TYPE_NORMAL_CHAT = 1;
    const USAGE_TYPE_PUBLIC_CHAT = 2;
    const USAGE_TYPE_INITIAL_CONTACT= 3;

    public $table = 'bot_messages';

    protected $fillable = [
        'bot_id',
        'body',
        'status',
        'usage_type'
    ];

    public static function usageTypeDescriptionPerId()
    {
        return [
            self::USAGE_TYPE_NORMAL_CHAT => 'Normal chat',
            self::USAGE_TYPE_PUBLIC_CHAT => 'Public chat',
            self::USAGE_TYPE_INITIAL_CONTACT => 'Initial contact',
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBotId()
    {
        return $this->bot_id;
    }

    public function setBotId(?string $botId)
    {
        $this->bot_id = $botId;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getUsageType()
    {
        return $this->usage_type;
    }

    public function setUsageType(string $usageType)
    {
        $this->usage_type = $usageType;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(User::class, 'bot_id');
    }

    public function peasants()
    {
        return $this->belongsToMany(User::class, 'user_bot_message', 'bot_message_id', 'user_id')
            ->withTimestamps();
    }
}
