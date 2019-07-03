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
}
