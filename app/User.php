<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username = '')
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email = '')
    {
        $this->email = $email;
    }

    public function getCreatedAt()
    {
        return $this->createAt;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function isAdmin()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('admin', $userRoles);
    }

    public function isOperator()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('operator', $userRoles);
    }

    /**
     * Get the meta associated with the user.
     */
    public function meta()
    {
        return $this->hasOne('App\UserMeta');
    }

    /**
     * Get the user that owns the meta.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany('App\UserImage')->orderBy('visible', 'desc');
    }

    public function visibleImages()
    {
        return $this->hasMany('App\UserImage')->where('visible', 1);
    }

    public function imagesNotProfile()
    {
        return $this->hasMany('App\UserImage')->where('profile', 0)->orderBy('visible', 'desc');
    }

    public function visibleImagesNotProfile()
    {
        return $this->hasMany('App\UserImage')->where('visible', 1)->where('profile', 0);
    }

    public function profileImage()
    {
        return $this->hasOne('App\UserImage')->where('profile', 1);
    }

    public function receivedFlirts()
    {
        return $this->hasMany('App\Flirt', 'recipient_id');
    }

    public function sentFlirts()
    {
        return $this->hasMany('App\Flirt', 'sender_id');
    }

    public function setEmailAttribute($email)
    {
        if (empty($email)) { // will check for empty string, null values, see php.net about it
            $this->attributes['email'] = null;
        } else {
            $this->attributes['email'] = $email;
        }
    }

    public function conversations()
    {
        return Conversation::with(['messages'])->where('user_a_id', $this->id)->orWhere('user_b_id', $this->id)->get();
    }
}
