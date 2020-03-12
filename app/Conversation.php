<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Conversation
 * @package App
 */
class Conversation extends TimeZonedModel
{
    use SoftDeletes;

    public $table = 'conversations';

    protected $appends = ['updatedAtHumanReadable'];

    protected $fillable = [
        'user_a_id',
        'user_b_id',
        'new_activity_for_user_a',
        'new_activity_for_user_b'
    ];

    public function getUpdatedAtHumanReadableAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->getUpdatedAt()))->diffForHumans();
    }

    /**
     * @return mixed
     */
    public function messages()
    {
        return $this->hasMany('App\ConversationMessage')->with([
            'sender',
            'recipient',
            'attachment'
        ]);
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

    public function newActivityParticipant()
    {
        return $this->belongsTo('App\User', 'new_activity_for_participant_id', 'id');
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

    public function setNewActivityForUserA(bool $value)
    {
        $this->new_activity_for_user_a = $value;
    }

    public function setNewActivityForUserB(bool $value)
    {
        $this->new_activity_for_user_b = $value;
    }

    public function getUserAId()
    {
        return $this->user_a_id;
    }

    public function getUserBId()
    {
        return $this->user_b_id;
    }

    public function getLockedByUserId()
    {
        return $this->locked_by_user_id;
    }

    /**
     * @param int|null $userId
     */
    public function setLockedByUserId(?int $userId)
    {
        $this->locked_by_user_id = $userId;
    }
}
