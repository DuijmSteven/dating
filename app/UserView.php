<?php

namespace App;

class UserView extends TimeZonedModel
{
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

    public function setAutomated(bool $automated)
    {
        $this->automated = $automated;
    }

    public function setViewerId(int $viewerId)
    {
        $this->viewer_id = $viewerId;
    }

    public function setViewedId(int $viewedId)
    {
        $this->viewed_id = $viewedId;
    }


}
