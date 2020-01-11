<?php

namespace App;

class ConversationNote extends TimeZonedModel
{
    public $table = 'conversation_notes';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'category_id',
        'title',
        'body',
    ];

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    public function noteCategory()
    {
        return $this->belongsTo('App\NoteCategory', 'category_id', 'id');
    }
}
