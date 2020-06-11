<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserAffiliateTracking extends TimeZonedModel
{
    const AFFILIATE_XPARTNERS = 'xpartners';
    const AFFILIATE_GOOGLE = 'google';

    const LEAD_STATUS_UNVALIDATED = 0;
    const LEAD_STATUS_VALIDATED = 1;

    const LEAD_ELIGIBILITY_PENDING = 0;
    const LEAD_ELIGIBILITY_INELIGIBLE = 1;
    const LEAD_ELIGIBILITY_ELIGIBLE = 2;

    public static function statusDescriptionPerId()
    {
        return [
            self::LEAD_STATUS_UNVALIDATED => 'Unvalidated',
            self::LEAD_STATUS_VALIDATED => 'Validated',
        ];
    }

    public static function eligibilityDescriptionPerId()
    {
        return [
            self::LEAD_ELIGIBILITY_PENDING => 'Pending',
            self::LEAD_ELIGIBILITY_INELIGIBLE => 'Ineligible',
            self::LEAD_ELIGIBILITY_ELIGIBLE => 'Eligible',
        ];
    }

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
        'affiliate',
        'lead_eligibility',
        'lead_status',
        'country_code',
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

    public function getLeadEligibility()
    {
        return $this->lead_eligibility;
    }

    public function setLeadEligibility(int $leadEligibility)
    {
        $this->lead_eligibility = $leadEligibility;
    }

    public function getCountryCode()
    {
        return $this->country_code;
    }

    public function setCountryCode(int $countryCode)
    {
        $this->country_code = $countryCode;
    }

    public function getLeadStatus()
    {
        return $this->lead_status;
    }

    public function setLeadStatus(int $leadStatus)
    {
        $this->lead_status = $leadStatus;
    }
}
