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
}
