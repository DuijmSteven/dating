<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBotMessage extends Model
{
    public $table = 'user_bot_message';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bot_message_id',
    ];

    public function setUserId(int $userId) {
        $this->user_id = $userId;
    }

    public function setBotMessageId(int $botMessageId) {
        $this->bot_message_id = $botMessageId;
    }
}
