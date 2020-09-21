<?php

namespace App;

use Carbon\Carbon;

class UserView extends TimeZonedModel
{
    const TYPE_SCHEDULED = 1;
    const TYPE_BOT_MESSAGE = 2;
    const TYPE_PEASANT = 3;
    const TYPE_OPERATOR_MESSAGE = 4;
    const TYPE_AUTOMATED = 5;

    public $table = 'user_views';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'viewer_id',
        'viewed_id',
        'type',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the meta.
     */
    public function viewed()
    {
        return $this->belongsTo(User::class, 'viewed_id', 'id');
    }

    public function viewer()
    {
        return $this->belongsTo(User::class, 'viewer_id', 'id');
    }

    public function setType(?int $type)
    {
        $this->type = $type;
    }

    public function setViewerId(int $viewerId)
    {
        $this->viewer_id = $viewerId;
    }

    public function setViewedId(int $viewedId)
    {
        $this->viewed_id = $viewedId;
    }

    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

}
