<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
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

    public function getRelationshipStatus()
    {
        return $this->relationship_status;
    }

    public function getBodyType()
    {
        return $this->body_type;
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
