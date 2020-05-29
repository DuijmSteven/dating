<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserAffiliateTracking extends TimeZonedModel
{
    const AFFILIATE_XPARTNERS = 'xpartners';

    public $table = 'user_affiliate_tracking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'click_id',
        'media_id',
        'affiliate'
    ];

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId(int $userId)
    {
        $this->user_id = $userId;
    }

    public function getClickId()
    {
        return $this->click_id;
    }

    public function setClickId(int $clickId)
    {
        $this->click_id = $clickId;
    }

    public function getAffiliate(): string
    {
        return $this->affiliate;
    }

    public function setAffiliate(string $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    public function getMediaId()
    {
        return $this->media_id;
    }

    public function setMediaId(int $mediaId)
    {
        $this->media_id = $mediaId;
    }
}
