<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 * @package App
 */
class Conversation extends Model
{
    public $table = 'conversations';

    protected $fillable = [
        'user_a_id',
        'user_b_id'
    ];

    /**
     * @return mixed
     */
    public function messages()
    {
        return $this->hasMany('App\ConversationMessage')->with([
            'sender',
            'recipient',
            'attachment'
        ])
        ->orderBy('created_at', 'asc');
    }

    /**
     * @return mixed
     */
    public function attachments()
    {
        return $this->hasMany('App\MessageAttachment', 'conversation_id')->orderBy('created_at', 'asc');
    }

    /**
     * @return mixed
     */
    public function userA()
    {
        return $this->belongsTo('App\User', 'user_a_id', 'id')->with(['meta', 'roles', 'images']);
    }

    /**
     * @return mixed
     */
    public function userB()
    {
        return $this->belongsTo('App\User', 'user_b_id', 'id')->with(['meta', 'roles', 'images']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany('App\ConversationNote');
    }

    public function getId() {
        return $this->id;
    }
}
