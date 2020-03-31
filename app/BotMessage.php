<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class BotMessage extends TimeZonedModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $table = 'bot_messages';

    protected $fillable = [
        'body',
        'status'
    ];

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
}
