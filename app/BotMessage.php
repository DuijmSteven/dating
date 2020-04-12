<?php

namespace App;

class BotMessage extends TimeZonedModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $table = 'bot_messages';

    protected $fillable = [
        'bot_id',
        'body',
        'status'
    ];

    public function getBotId()
    {
        return $this->bot_id;
    }

    public function setBotId(string $botId)
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(User::class, 'bot_id');
    }
}
