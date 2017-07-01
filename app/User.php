<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 */
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
        'username',
        'email',
        'password',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $allowedImageTypes = [
        'profile',
        'other'
    ];

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username = '')
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email = '')
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('admin', $userRoles);
    }

    /**
     * @return bool
     */
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
        return $this->belongsToMany('App\Role');
    }

    /**
     * @return mixed
     */
    public function images()
    {
        return $this->hasMany('App\UserImage')->orderBy('visible', 'desc');
    }

    /**
     * @return mixed
     */
    public function visibleImages()
    {
        return $this->hasMany('App\UserImage')->where('visible', 1);
    }

    /**
     * @return mixed
     */
    public function imagesNotProfile()
    {
        return $this->hasMany('App\UserImage')->where('profile', 0)->orderBy('visible', 'desc');
    }

    /**
     * @return mixed
     */
    public function visibleImagesNotProfile()
    {
        return $this->hasMany('App\UserImage')->where('visible', 1)->where('profile', 0);
    }

    /**
     * @return mixed
     */
    public function profileImage()
    {
        return $this->hasOne('App\UserImage')->where('profile', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedFlirts()
    {
        return $this->hasMany('App\Flirt', 'recipient_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentFlirts()
    {
        return $this->hasMany('App\Flirt', 'sender_id');
    }

    /**
     * @param $email
     */
    public function setEmailAttribute($email)
    {
        if (empty($email)) { // will check for empty string, null values, see php.net about it
            $this->attributes['email'] = null;
        } else {
            $this->attributes['email'] = $email;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function conversations()
    {
        return Conversation::with(['messages'])->where('user_a_id', $this->id)->orWhere('user_b_id', $this->id)->get();
    }

    /**
     * @return $this
     */
    public function format()
    {
        return $this->formatImages();
    }

    /**
     * Formats the images on the User object
     */
    private function formatImages()
    {
        $this->profile_image = null;
        $this->other_images = null;

        $this->categorizeImages();

        // unset original images property, no longer needed
        unset($this->images);

        return $this;
    }

    /**
     * @param $type
     * @return bool
     * @throws \Exception
     */
    private function hasImageTypeSet($type)
    {
        if (!in_array($type, $this->allowedImageTypes)) {
            throw new \Exception;
        }

        return (isset($this->{$type . '_images'}));
    }

    /**
     * @param $image
     * @throws \Exception
     */
    private function assignImage($image)
    {
        if ($image->profile === 1) {
            $this->assignProfileImage($image);
        } else {
            $this->assignOtherImage($image);
        }
    }

    /**
     * @param $image
     * @throws \Exception
     */
    private function assignOtherImage($image)
    {
        if ($this->hasImageTypeSet('other')) {
            $this->other_images->push($image);
        } else {
            $this->other_images = collect([$image]);
        }
    }

    /**
     * @param $image
     */
    private function assignProfileImage($image)
    {
        $this->profile_image = $image;
    }

    /**
     * @param array $allowedImageTypes
     * @return User
     */
    public function setAllowedImageTypes($allowedImageTypes)
    {
        $this->allowedImageTypes = $allowedImageTypes;
        return $this;
    }

    /**
     * @return $this
     */
    private function categorizeImages()
    {
        $images = $this->images();

        foreach ($images as $image) {
            $this->assignImage($image);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasProfileImage()
    {
        if (!isset($this->profile_image) || is_null($this->profile_image)) {
            return false;
        }

        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne('App\UserAccount');
    }
}
