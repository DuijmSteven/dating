<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    const TYPE_ADMIN = 1;
    const TYPE_PEASANT = 2;
    const TYPE_BOT = 3;
    const TYPE_OPERATOR = 4;

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

    public function getCreatedBotIdsAttribute()
    {
        return $this->createdBots->pluck('id')->toArray();
    }

    public function getProfileImageUrlAttribute()
    {
        return \StorageHelper::profileImageUrl($this);
    }

    public function getProfileImageUrlThumbAttribute()
    {
        return \StorageHelper::profileImageUrl($this, true);
    }

    public function isPayingUser(): bool
    {
        if ($this->account->credits > 0) {
            $completedPaymentsCount = 0;

            foreach ($this->payments as $payment) {
                if ($payment->status == Payment::STATUS_COMPLETED) {
                    $completedPaymentsCount++;
                }
            }

            if ($completedPaymentsCount < 1) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function getHasRecentlyAcceptedProfileCompletionMessageAttribute()
    {
        $milestones = $this->where('id', \Auth::user()->getId())->whereHas('milestones', function ($query) {
            $query->where('id', Milestone::ACCEPTED_PROFILE_COMPLETION_MESSAGE);
            $query->where('milestone_user.created_at', '>=', Carbon::now('Europe/Amsterdam')->subDays(5)->toDateTimeString());
        })->get();

        return count($milestones) !== 0;
    }

    public function getProfileRatioFilledAttribute()
    {
        $countFilled = 0;

        if ($this->meta->getAboutMe()) {
            $countFilled++;
        }

        if ($this->meta->getDob()) {
            $countFilled++;
        }

        if ($this->meta->getCity()) {
            $countFilled++;
        }

        if ($this->meta->getDrinkingHabits()) {
            $countFilled++;
        }

        if ($this->meta->getSmokingHabits()) {
            $countFilled++;
        }

        if ($this->meta->getEyeColor()) {
            $countFilled++;
        }

        if ($this->meta->getHairColor()) {
            $countFilled++;
        }

        if ($this->meta->getHeight()) {
            $countFilled++;
        }

        if ($this->meta->getRelationshipStatus()) {
            $countFilled++;
        }

        if ($this->meta->getBodyType()) {
            $countFilled++;
        }

        if ($this->profileImage) {
            $countFilled++;
        }

        return round($countFilled/11, 2);
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
        'conversation_manager_state',
        'created_by_id'
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

    public function setDeactivatedAt(Carbon $deactivatedAt)
    {
        $this->deactivated_at = $deactivatedAt;
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
    public function isEditor()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('editor', $userRoles);
    }

    /**
     * @return bool
     */
    public function isPeasant()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('peasant', $userRoles);
    }

    public function getCreatedById(): ?int
    {
        return $this->created_by_id;
    }

    public function setCreatedById(int $createdById = null)
    {
        $this->created_by_id = $createdById;
    }

    /**
     * @return bool
     */
    public function isBot()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role['name'];
        }
        return in_array('bot', $userRoles);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function milestones()
    {
        return $this->belongsToMany('App\Milestone')->withTimestamps();
    }

    public function hasMilestone(int $milestoneId)
    {
        return in_array($milestoneId, $this->milestones->pluck('id')->toArray());
    }

    public function emailTypeInstances()
    {
        return $this->belongsToMany(EmailType::class, 'user_email_type_instances', 'receiver_id', 'email_type_id')->withTimestamps();
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
    public function createdBots()
    {
        return $this->hasMany(User::class, 'created_by_id');
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


    public function views()
    {
        return $this->hasMany(UserView::class, 'viewed_id', 'id');
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

    public function completedPayments()
    {
        return $this->hasMany('App\Payment')->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'desc');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ConversationMessage::class, 'sender_id');
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
