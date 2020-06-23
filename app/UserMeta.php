<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    const EMAIL_VERIFIED_FALSE = 0;
    const EMAIL_VERIFIED_TRUE = 1;
    const EMAIL_VERIFIED_RISKY = 2;
    const EMAIL_VERIFIED_UNKNOWN = 3;
    const EMAIL_VERIFIED_OTHER = 4;

    const EMAIL_VERIFICATION_STATUS_PENDING = 0;
    const EMAIL_VERIFICATION_STATUS_DONE = 1;
    const EMAIL_VERIFICATION_STATUS_FAILED = 2;

    public static function emailVerifiedDescriptionPerId()
    {
        return [
            self::EMAIL_VERIFIED_FALSE => 'Unverified',
            self::EMAIL_VERIFIED_TRUE => 'Verified',
            self::EMAIL_VERIFIED_RISKY => 'Risky',
            self::EMAIL_VERIFIED_UNKNOWN => 'Unknown',
            self::EMAIL_VERIFIED_OTHER => 'Other',
        ];
    }

    public static function emailVerificationStatusDescriptionPerId()
    {
        return [
            self::EMAIL_VERIFICATION_STATUS_PENDING => 'Pending',
            self::EMAIL_VERIFICATION_STATUS_DONE => 'Done',
            self::EMAIL_VERIFICATION_STATUS_FAILED => 'Failed',
        ];
    }

    public $table = 'user_meta';

    protected $dates = [
        'dob'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'dob',
        'gender',
        'looking_for_gender',
        'relationship_status',
        'city',
        'lat',
        'lng',
        'country',
        'height',
        'body_type',
        'eye_color',
        'hair_color',
        'smoking_habits',
        'drinking_habits',
        'country',
        'about_me',
        'looking_for',
        'logins_count',
        'email_verified',
        'email_verification_status',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function getLookingForGender()
    {
        return $this->looking_for_gender;
    }

    public function getDrinkingHabits()
    {
        return $this->drinking_habits;
    }

    public function getSmokingHabits()
    {
        return $this->smoking_habits;
    }

    public function getEyeColor()
    {
        return $this->eye_color;
    }

    public function getHairColor()
    {
        return $this->hair_color;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getLoginsCount()
    {
        return $this->logins_count;
    }

    public function setLoginsCount(int $loginsCount)
    {
        $this->logins_count = $loginsCount;
    }

    public function getRelationshipStatus()
    {
        return $this->relationship_status;
    }

    public function getBodyType()
    {
        return $this->body_type;
    }

    public function getEmailVerificationStatus()
    {
        return $this->email_verification_status;
    }

    public function setEmailVerificationStatus(int $emailVerificationStatus)
    {
        $this->email_verification_status = $emailVerificationStatus;
    }

    public function getEmailVerified()
    {
        return $this->email_verified;
    }

    public function setEmailVerified(int $emailVerified)
    {
        $this->email_verified = $emailVerified;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function getAboutMe()
    {
        return $this->about_me;
    }

    public function getDob()
    {
        return $this->dob;
    }
}
