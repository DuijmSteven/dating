<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationNote extends Model
{
    public $table = 'conversation_notes';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'category',
        'title',
        'body',
    ];

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }
}
