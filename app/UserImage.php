<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    public $table = 'user_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'filename',
        'visible',
        'profile'
    ];

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'users', 'id', 'user_id');
    }
}
