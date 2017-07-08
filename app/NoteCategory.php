<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoteCategory extends Model
{
    public $table = 'note_categories';

    public $timestamps = false;

    public function notes()
    {
        return $this->hasMany('App\ConversationNote', 'id', 'category_id');
    }
}
