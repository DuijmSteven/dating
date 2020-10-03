<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpenConversationPartner extends Model
{
    public $table = 'open_conversation_partners';

    public $fillable = [
        'user_id',
        'partner_id',
        'state'
    ];

    public function setUserId(int $userId)
    {
        $this->user_id = $userId;
    }

    public function setPartnerId(int $partnerId)
    {
        $this->partner_id = $partnerId;
    }

    public function setState(bool $state)
    {
        $this->state = $state;
    }
}
