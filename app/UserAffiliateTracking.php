<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserAffiliateTracking extends TimeZonedModel
{
    const AFFILIATE_XPARTNERS = 'xpartners';
    const AFFILIATE_GOOGLE = 'google';
    const AFFILIATE_DATECENTRALE = 'datecentrale';

    const PUBLISHER_GRATIS_PORNO_FILM = 1;
    const PUBLISHER_EROTIC_FOR_YOU = 2;
    const PUBLISHER_MEDIA_BUYING_PUSH_TRAFFIC = 3;
    const PUBLISHER_VERGELIJKERS_XPARTNERS = 4;
    const PUBLISHER_DATINGSITE_CHECKER = 5;
    const PUBLISHER_GEILE_SEX_FILMS = 6;
    const PUBLISHER_GENOTSPLEKJE = 7;
    const PUBLISHER_DATECENTRALE = 8;
    const PUBLISHER_GOOGLE_NL= 9;
    const PUBLISHER_GOOGLE_BE= 10;

    const LEAD_STATUS_UNVALIDATED = 0;
    const LEAD_STATUS_VALIDATED = 1;

    const LEAD_ELIGIBILITY_PENDING = 0;
    const LEAD_ELIGIBILITY_INELIGIBLE = 1;
    const LEAD_ELIGIBILITY_ELIGIBLE = 2;

    public static function publisherDescriptionPerId()
    {
        return [
            self::PUBLISHER_GRATIS_PORNO_FILM => 'gratispornofilm.nl',
            self::PUBLISHER_EROTIC_FOR_YOU => 'eroticforyou.nl',
            self::PUBLISHER_MEDIA_BUYING_PUSH_TRAFFIC => 'Media Buying Push Traffic',
            self::PUBLISHER_VERGELIJKERS_XPARTNERS => 'vergelijkers.xpartners.xxx',
            self::PUBLISHER_DATINGSITE_CHECKER => 'datingsitechecker.nl',
            self::PUBLISHER_GEILE_SEX_FILMS => 'geilesexfilms',
            self::PUBLISHER_GENOTSPLEKJE => 'genotsplekje',
            self::PUBLISHER_DATECENTRALE => 'datecentrale.nl',
        ];
    }

    public static function mediaIdsPerPublisher()
    {
        return [
            self::PUBLISHER_GRATIS_PORNO_FILM => [147479],
            self::PUBLISHER_EROTIC_FOR_YOU => [147200],
            self::PUBLISHER_MEDIA_BUYING_PUSH_TRAFFIC => [147333],
            self::PUBLISHER_VERGELIJKERS_XPARTNERS => [145984],
            self::PUBLISHER_DATINGSITE_CHECKER => [147374],
            self::PUBLISHER_GEILE_SEX_FILMS => [147488],
            self::PUBLISHER_GENOTSPLEKJE => [147264],
        ];
    }

    public static function publisherPerMediaId()
    {
        return [
            147779 => self::PUBLISHER_GRATIS_PORNO_FILM,
            147200 => self::PUBLISHER_EROTIC_FOR_YOU,
            147333 => self::PUBLISHER_MEDIA_BUYING_PUSH_TRAFFIC,
            145984 => self::PUBLISHER_VERGELIJKERS_XPARTNERS,
            147374 => self::PUBLISHER_DATINGSITE_CHECKER,
            147488 => self::PUBLISHER_GEILE_SEX_FILMS,
            147264 => self::PUBLISHER_GENOTSPLEKJE,
        ];
    }

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
        'publisher'
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

    public function getPublisher(): ?int
    {
        return $this->publisher;
    }

    public function setPublisher(?int $publisher)
    {
        $this->publisher = $publisher;
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
