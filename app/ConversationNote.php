<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationNote extends Model
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
