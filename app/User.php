<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['deactivated_at'];

    protected $appends = [
        'profileImageUrl',
        'profileImageUrlThumb'
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    public function getProfileImageUrlAttribute()
    {
        return \StorageHelper::profileImageUrl($this);
    }

    public function getProfileImageUrlThumbAttribute()
    {
        return \StorageHelper::profileImageUrl($this, true);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'active',
        'conversation_manager_state'
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
    public function getId()
    {
        return $this->id;
    }

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
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale = '')
    {
        $this->locale = $locale;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
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
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function getConversationManagerState()
    {
        return $this->conversation_manager_state;
    }

    public function setConversationManagerState($state)
    {
        $this->conversation_manager_state = $state;
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
     * Get the user that owns the meta.
     */
    public function emailTypes()
    {
        return $this->belongsToMany('App\EmailType');
    }

    public function addEmailType(EmailType $type)
    {
        $this->emailTypes()->save($type);   // add friend
    }

    public function removeEmailType($typeId)
    {
        $this->emailTypes()->detach($typeId);   // remove friend
    }

    public function openConversationPartners()
    {
        return $this->belongsToMany(
            'App\User',
            'open_conversation_partners',
            'user_id',
            'partner_id'
        )->withTimestamps();
    }

    public function addOpenConversationPartner(User $partner, $state)
    {
        $this->openConversationPartners()->save($partner, ['state' => $state]);   // add friend
    }

    public function removeOpenConversationPartner($partnerId)
    {
        $this->openConversationPartners()->detach($partnerId);   // remove friend
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function botCategories()
    {
        return $this->belongsToMany('App\BotCategory');
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
    public function invisibleImages()
    {
        return $this->hasMany('App\UserImage')->where('visible', 0);
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
     * @return mixed
     */
    public function nonProfileImages()
    {
        return $this->hasMany('App\UserImage')->where('profile', 0);
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

    public function payments()
    {
        return $this->hasMany('App\Payment');
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
        if (!isset($this->profileImage) || is_null($this->profileImage)) {
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany('App\Activity');
    }
}
