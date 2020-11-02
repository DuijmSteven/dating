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

    public $timestamps = true;

    const CYCLE_STAGE_NEW = 0;
    const CYCLE_STAGE_UNREPLIED = 1;
    const CYCLE_STAGE_STOPPED = 2;
    const CYCLE_STAGE_STOPPED_AND_REATTEMPTED = 3;
    const CYCLE_STAGE_BALL_IN_PEASANTS_COURT = 4;

    public $table = 'conversations';

    public $dates = [
        'locked_at',
        'deleted_at',
        'replyable_at'
    ];

    protected $appends = [
        'updatedAtHumanReadable',
        'originSite'
    ];

    protected $fillable = [
        'user_a_id',
        'user_b_id',
        'new_activity_for_user_a',
        'new_activity_for_user_b',
        'replyable_at',
        'cycle_stage'
    ];

    public function getUpdatedAtHumanReadableAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function getOriginSiteAttribute()
    {
        return config('app.site_id');
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
        return $this->belongsTo('App\User', 'user_a_id', 'id')->with(['meta', 'roles', 'images', 'profileImage', 'account']);
    }

    /**
     * @return mixed
     */
    public function userB()
    {
        return $this->belongsTo('App\User', 'user_b_id', 'id')->with(['meta', 'roles', 'images', 'profileImage', 'account']);
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
    public function setLockedByUserId(?int $cycleStage)
    {
        $this->locked_by_user_id = $cycleStage;
    }

    public function getCycleStage()
    {
        return $this->cycle_stage;
    }

    /**
     * @param int|null $cycleStage
     */
    public function setCycleStage(?int $cycleStage)
    {
        $this->cycle_stage = $cycleStage;
    }

    /**
     * @return Carbon|null
     */
    public function getLockedAt()
    {
        return $this->locked_at;
    }

    /**
     * @param Carbon|null $lockedAt
     */
    public function setLockedAt(?Carbon $lockedAt)
    {
        $this->locked_at = $lockedAt;
    }

    public function getReplyableAt()
    {
        return $this->replyable_at;
    }

    public function setReplyableAt($replyableAt)
    {
        $this->replyable_at = $replyableAt;
    }

    /**
     * @return Carbon|null
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param Carbon|null $deletedAt
     */
    public function setDeletedAt(?Carbon $deletedAt)
    {
        $this->deleted_at = $deletedAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }
}
