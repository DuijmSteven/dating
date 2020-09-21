<?php

namespace App;

use App\Managers\ConversationManager;
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
    const TYPE_EDITOR = 5;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    const COMMON_RELATIONS = [
        'meta',
        'roles',
        'profileImage',
        'account'
    ];

    const OPERATOR_RELATIONS = [
        'operatorMessages'
    ];

    const OPERATOR_RELATION_COUNTS = [
        'operatorMessages',
        'operatorMessagesLastMonth',
        'operatorMessagesThisMonth',
        'operatorMessagesLastWeek',
        'operatorMessagesThisWeek',
        'operatorMessagesYesterday',
        'operatorMessagesToday',
        'operatorMessagesToStoppedConversations',
        'operatorMessagesToStoppedConversationsLastMonth',
        'operatorMessagesToStoppedConversationsThisMonth',
        'operatorMessagesToStoppedConversationsLastWeek',
        'operatorMessagesToStoppedConversationsThisWeek',
        'operatorMessagesToStoppedConversationsYesterday',
        'operatorMessagesToStoppedConversationsToday'
    ];

    const EDITOR_RELATIONS = [
    ];

    const EDITOR_RELATION_COUNTS = [
        'createdBots',
        'createdBotsLastMonth',
        'createdBotsThisMonth',
        'createdBotsLastWeek',
        'createdBotsThisWeek',
        'createdBotsYesterday',
        'createdBotsToday'
    ];

    const ADMIN_RELATIONS = [

    ];

    const ADMIN_RELATION_COUNTS = [

    ];

    const PEASANT_RELATIONS = [
        'images',
        'completedPayments',
        'hasViewed',
        'hasViewedUnique',
        'affiliateTracking',
    ];

    const PEASANT_RELATION_COUNTS = [
        'messaged',
        'messagedToday',
        'messagedYesterday',
        'messagedThisWeek',
        'messagedLastWeek',
        'messagedYesterday',
        'messagedThisMonth',
        'messagedLastMonth',
        'messages',
        'messagesToday',
        'messagesYesterday',
        'messagesThisWeek',
        'messagesLastWeek',
        'messagesYesterday',
        'messagesThisMonth',
        'messagesLastMonth',
        'conversationsAsUserA',
        'conversationsAsUserB',
        'payments',
        'botMessagesReceived'
    ];

    const BOT_RELATIONS = [
        'views',
        'uniqueViews',
        'images',
        'createdByOperator'
    ];

    const BOT_RELATION_COUNTS = [
        'messaged',
        'messagedToday',
        'messagedYesterday',
        'messagedThisWeek',
        'messagedLastWeek',
        'messagedYesterday',
        'messagedThisMonth',
        'messagedLastMonth',
        'messages',
        'messagesToday',
        'messagesYesterday',
        'messagesThisWeek',
        'messagesLastWeek',
        'messagesYesterday',
        'messagesThisMonth',
        'messagesLastMonth',
        'conversationsAsUserA',
        'conversationsAsUserB',
        'views',
        'botMessages'
    ];

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

    protected $dates = [
        'deactivated_at',
        'last_online_at',
        'locked_at'
    ];

    protected $appends = [
        'profileImageUrl',
        'profileImageUrlThumb'
    ];

    public function getIsMailableAttribute()
    {
        return $this->meta->getEmailVerified() === UserMeta::EMAIL_VERIFIED_DELIVERABLE ||
        $this->meta->getEmailVerified() === UserMeta::EMAIL_VERIFIED_RISKY;
    }

    // checks if user is new and viewing profiles for the first time, in order to create automated profile view and maybe message from the bot he is viewing
    public function getIsFullyImpressionableAttribute()
    {
        $registeredRecently = $this->getCreatedAt()->diffInMinutes(Carbon::now()) < 5;
        $conversationsCount = $this->conversations_as_user_b_count;

        return $registeredRecently && $conversationsCount < 2;
    }

    public function getShouldBeBotMessagedAttribute()
    {
        $conversationsCount = $this->conversations_as_user_b_count;

        return $conversationsCount < 2;
    }

    public function getIsPartlyImpressionableAttribute()
    {
        $registeredRecently = $this->getCreatedAt()->diffInMinutes(Carbon::now()) >= 5 && $this->getCreatedAt()->diffInDays(Carbon::now() <= 2);
        $conversationsCount = $this->conversations_as_user_b_count;

        return $registeredRecently && $conversationsCount < 4;
    }

    public function messagedVsMessagesPercentage()
    {
        if ($this->messages_count > 0) {
            return number_format($this->messaged_count / $this->messages_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }

    public function messagedVsMessagesPercentageToday()
    {
        if ($this->messages_today_count > 0) {
            return number_format($this->messaged_today_count / $this->messages_today_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }

    public function messagedVsMessagesPercentageYesterday()
    {
        if ($this->messages_yesterday_count > 0) {
            return number_format($this->messaged_yesterday_count / $this->messages_yesterday_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }


    public function messagedVsMessagesPercentageThisWeek()
    {
        if ($this->messages_this_week_count > 0) {
            return number_format($this->messaged_this_week_count / $this->messages_this_week_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }

    public function messagedVsMessagesPercentageLastWeek()
    {
        if ($this->messages_last_week_count > 0) {
            return number_format($this->messaged_last_week_count / $this->messages_last_week_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }

    public function messagedVsMessagesPercentageThisMonth()
    {
        if ($this->messages_this_month_count > 0) {
            return number_format($this->messaged_this_month_count / $this->messages_this_month_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }

    public function messagedVsMessagesPercentageLastMonth()
    {
        if ($this->messages_last_month_count > 0) {
            return number_format($this->messaged_last_month_count / $this->messages_last_month_count * 100, 0) . '%';
        } else {
            return 'No messages sent';
        }
    }

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
        'created_by_id',
        'tracked',
        'api_token',
        'first_name',
        'last_name',
        'postal_code',
        'street_name'
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
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName = '')
    {
        $this->first_name = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName = '')
    {
        $this->last_name = $lastName;
    }

    /**
     * @return mixed
     */
    public function getStreetName()
    {
        return $this->street_name;
    }

    /**
     * @param string $streetName
     */
    public function setStreetName($streetName = '')
    {
        $this->street_name = $streetName;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode = '')
    {
        $this->postal_code = $postalCode;
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
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->api_token;
    }


    public function setApiToken(string $apiToken)
    {
        $this->api_token = $apiToken;
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

    public function setDeactivatedAt(?Carbon $deactivatedAt)
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
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getDeactivatedAt()
    {
        return $this->deactivated_at;
    }

    public function getLastOnlineAt(): ?Carbon
    {
        return $this->last_online_at;
    }

    public function setLastOnlineAt(Carbon $datetime)
    {
        $this->last_online_at = $datetime;
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
        return $this->hasOne(UserMeta::class);
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

    public function botMessagesReceived()
    {
        return $this->belongsToMany(BotMessage::class, 'user_bot_message', 'bot_message_id', 'user_id')
            ->withTimestamps();
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

    public function lockedConversations()
    {
        $minutesAgo = (new Carbon('now'))
            ->subMinutes(ConversationManager::CONVERSATION_LOCKING_TIME);

        return $this
            ->hasMany(Conversation::class, 'locked_by_user_id')
            ->where('locked_at', '>=', $minutesAgo);
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
    public function createdByOperator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * @return mixed
     */
    public function conversationsAsOperator()
    {
        return Conversation::whereHas('messages', function ($query) {
                $query->where('operator_id', $this->getId());
            });
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

    public function hasViewed()
    {
        return $this->hasMany(UserView::class, 'viewer_id', 'id');
    }

    public function hasViewedUnique()
    {
        return $this->hasMany(UserView::class, 'viewer_id', 'id')->groupBy('viewed_id');
    }

    public function views()
    {
        return $this->hasMany(UserView::class, 'viewed_id', 'id');
    }

    public function uniqueViews()
    {
        return $this->hasMany(UserView::class, 'viewed_id', 'id')->groupBy('viewer_id');
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
        return $this->hasMany(Payment::class);
    }

    public function completedPayments()
    {
        return $this->hasMany(Payment::class)->where('status', Payment::STATUS_COMPLETED)->orderBy('created_at', 'desc');
    }

    public function publicChatItems()
    {
        return $this->hasMany(PublicChatItem::class, 'sender_id');
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

//    public function conversations()
//    {
//        return $this->where('user_a_id', $this->id)->orWhere('user_b_id', $this->id)->with(['messages']);
//    }

    public function conversationsAsUserA()
    {
        return $this->hasMany(Conversation::class, 'user_a_id')->withTrashed();
    }

    public function conversationsAsUserB()
    {
        return $this->hasMany(Conversation::class, 'user_b_id')->withTrashed();
    }

    public function publicChatMessages()
    {
        return $this->hasMany(PublicChatItem::class, 'sender_id')->orderBy('published_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messaged()
    {
        return $this->hasMany(ConversationMessage::class, 'recipient_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ConversationMessage::class, 'sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagesAsOperator()
    {
        return $this->hasMany(ConversationMessage::class, 'operator_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdBotsToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(User::class, 'created_by_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday);
    }

    public function createdBotsYesterday()
    {
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->startOfDay()->subDay();
        $endOfYesterday = $startOfYesterday->copy()->endOfDay();

        $startOfYesterdayUtc = $startOfYesterday->setTimezone('UTC');
        $endOfYesterdayUtc = $endOfYesterday->setTimezone('UTC');

        return $this->hasMany(User::class, 'created_by_id')
            ->where('created_at', '>=', $startOfYesterdayUtc)
            ->where('created_at', '<=', $endOfYesterdayUtc);
    }

    public function createdBotsThisWeek()
    {
        $startOfThisWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfThisWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        return $this->hasMany(User::class, 'created_by_id')
            ->where('created_at', '>=', $startOfThisWeek)
            ->where('created_at', '<=', $endOfThisWeek);
    }

    public function createdBotsLastWeek()
    {
        $startOfLastWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->subWeek();
        $endOfLastWeek = $startOfLastWeek->copy()->endOfWeek();

        $startOfLastWeekUtc = $startOfLastWeek->setTimezone('UTC');
        $endOfLastWeekUtc = $endOfLastWeek->setTimezone('UTC');

        return $this->hasMany(User::class, 'created_by_id')
            ->where('created_at', '>=', $startOfLastWeekUtc)
            ->where('created_at', '<=', $endOfLastWeekUtc);
    }

    public function createdBotsThisMonth()
    {
        $startOfThisMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfThisMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        return $this->hasMany(User::class, 'created_by_id')
            ->where('created_at', '>=', $startOfThisMonth)
            ->where('created_at', '<=', $endOfThisMonth);
    }

    public function createdBotsLastMonth()
    {
        $startOfLastMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfLastMonth = $startOfLastMonth->copy()->endOfMonth();

        $startOfLastMonthUtc = $startOfLastMonth->setTimezone('UTC');
        $endOfLastMonthUtc = $endOfLastMonth->setTimezone('UTC');

        return $this->hasMany(User::class, 'created_by_id')
            ->where('created_at', '>=', $startOfLastMonthUtc)
            ->where('created_at', '<=', $endOfLastMonthUtc);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagesTodayInUniqueConversations()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday)
            ->groupBy('conversation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagesRecentlyInUniqueConversations()
    {
        $tenMinutesAgo = Carbon::now('Europe/Amsterdam')->subMinutes(10)->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $tenMinutesAgo)
            ->where('created_at', '<=', $endOfToday)
            ->groupBy('conversation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversationsWithRepliesToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday)
            ->groupBy('conversation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagedRecentlyInUniqueConversations()
    {
        $tenMinutesAgo = Carbon::now('Europe/Amsterdam')->subMinutes(10)->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $tenMinutesAgo)
            ->where('created_at', '<=', $endOfToday)
            ->groupBy('conversation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagedToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday);
    }

    public function messagedYesterday()
    {
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->startOfDay()->subDay();
        $endOfYesterday = $startOfYesterday->copy()->endOfDay();

        $startOfYesterdayUtc = $startOfYesterday->setTimezone('UTC');
        $endOfYesterdayUtc = $endOfYesterday->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfYesterdayUtc)
            ->where('created_at', '<=', $endOfYesterdayUtc);
    }

    public function messagedThisWeek()
    {
        $startOfThisWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfThisWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfThisWeek)
            ->where('created_at', '<=', $endOfThisWeek);
    }

    public function messagedLastWeek()
    {
        $startOfLastWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->subWeek();
        $endOfLastWeek = $startOfLastWeek->copy()->endOfWeek();

        $startOfLastWeekUtc = $startOfLastWeek->setTimezone('UTC');
        $endOfLastWeekUtc = $endOfLastWeek->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfLastWeekUtc)
            ->where('created_at', '<=', $endOfLastWeekUtc);
    }

    public function messagedThisMonth()
    {
        $startOfThisMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfThisMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfThisMonth)
            ->where('created_at', '<=', $endOfThisMonth);
    }

    public function messagedLastMonth()
    {
        $startOfLastMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfLastMonth = $startOfLastMonth->copy()->endOfMonth();

        $startOfLastMonthUtc = $startOfLastMonth->setTimezone('UTC');
        $endOfLastMonthUtc = $endOfLastMonth->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'recipient_id')
            ->where('created_at', '>=', $startOfLastMonthUtc)
            ->where('created_at', '<=', $endOfLastMonthUtc);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagesToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday);
    }

    public function messagesYesterday()
    {
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->startOfDay()->subDay();
        $endOfYesterday = $startOfYesterday->copy()->endOfDay();

        $startOfYesterdayUtc = $startOfYesterday->setTimezone('UTC');
        $endOfYesterdayUtc = $endOfYesterday->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfYesterdayUtc)
            ->where('created_at', '<=', $endOfYesterdayUtc);
    }

    public function messagesThisWeek()
    {
        $startOfThisWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfThisWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfThisWeek)
            ->where('created_at', '<=', $endOfThisWeek);
    }

    public function messagesLastWeek()
    {
        $startOfLastWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->subWeek();
        $endOfLastWeek = $startOfLastWeek->copy()->endOfWeek();

        $startOfLastWeekUtc = $startOfLastWeek->setTimezone('UTC');
        $endOfLastWeekUtc = $endOfLastWeek->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfLastWeekUtc)
            ->where('created_at', '<=', $endOfLastWeekUtc);
    }

    public function messagesThisMonth()
    {
        $startOfThisMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfThisMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfThisMonth)
            ->where('created_at', '<=', $endOfThisMonth);
    }

    public function messagesLastMonth()
    {
        $startOfLastMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfLastMonth = $startOfLastMonth->copy()->endOfMonth();

        $startOfLastMonthUtc = $startOfLastMonth->setTimezone('UTC');
        $endOfLastMonthUtc = $endOfLastMonth->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'sender_id')
            ->where('created_at', '>=', $startOfLastMonthUtc)
            ->where('created_at', '<=', $endOfLastMonthUtc);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorMessagesToStoppedConversations()
    {
        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorMessagesToStoppedConversationsToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    public function operatorMessagesToStoppedConversationsYesterday()
    {
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->startOfDay()->subDay();
        $endOfYesterday = $startOfYesterday->copy()->endOfDay();

        $startOfYesterdayUtc = $startOfYesterday->setTimezone('UTC');
        $endOfYesterdayUtc = $endOfYesterday->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfYesterdayUtc)
            ->where('created_at', '<=', $endOfYesterdayUtc)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    public function operatorMessagesToStoppedConversationsThisWeek()
    {
        $startOfThisWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfThisWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfThisWeek)
            ->where('created_at', '<=', $endOfThisWeek)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    public function operatorMessagesToStoppedConversationsLastWeek()
    {
        $startOfLastWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->subWeek();
        $endOfLastWeek = $startOfLastWeek->copy()->endOfWeek();

        $startOfLastWeekUtc = $startOfLastWeek->setTimezone('UTC');
        $endOfLastWeekUtc = $endOfLastWeek->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfLastWeekUtc)
            ->where('created_at', '<=', $endOfLastWeekUtc)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorMessagesToStoppedConversationsThisMonth()
    {
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfMonth)
            ->where('created_at', '<=', $endOfMonth)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    public function operatorMessagesToStoppedConversationsLastMonth()
    {
        $startOfLastMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfLastMonth = $startOfLastMonth->copy()->endOfMonth();

        $startOfLastMonthUtc = $startOfLastMonth->setTimezone('UTC');
        $endOfLastMonthUtc = $endOfLastMonth->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfLastMonthUtc)
            ->where('created_at', '<=', $endOfLastMonthUtc)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorMessages()
    {
        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('operator_message_type', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorMessagesToday()
    {
        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfToday)
            ->where('created_at', '<=', $endOfToday)
            ->where('operator_message_type', null);
    }

    public function operatorMessagesYesterday()
    {
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->startOfDay()->subDay();
        $endOfYesterday = $startOfYesterday->copy()->endOfDay();

        $startOfYesterdayUtc = $startOfYesterday->setTimezone('UTC');
        $endOfYesterdayUtc = $endOfYesterday->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfYesterdayUtc)
            ->where('created_at', '<=', $endOfYesterdayUtc)
            ->where('operator_message_type', null);
    }

    public function operatorMessagesThisWeek()
    {
        $startOfThisWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->setTimezone('UTC');
        $endOfThisWeek = Carbon::now('Europe/Amsterdam')->endOfWeek()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfThisWeek)
            ->where('created_at', '<=', $endOfThisWeek)
            ->where('operator_message_type', null);
    }

    public function operatorMessagesLastWeek()
    {
        $startOfLastWeek = Carbon::now('Europe/Amsterdam')->startOfWeek()->subWeek();
        $endOfLastWeek = $startOfLastWeek->copy()->endOfWeek();

        $startOfLastWeekUtc = $startOfLastWeek->setTimezone('UTC');
        $endOfLastWeekUtc = $endOfLastWeek->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfLastWeekUtc)
            ->where('created_at', '<=', $endOfLastWeekUtc)
            ->where('operator_message_type', null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operatorMessagesThisMonth()
    {
        $startOfMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now('Europe/Amsterdam')->endOfMonth()->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfMonth)
            ->where('created_at', '<=', $endOfMonth)
            ->where('operator_message_type', null);
    }

    public function operatorMessagesLastMonth()
    {
        $startOfLastMonth = Carbon::now('Europe/Amsterdam')->startOfMonth()->subMonth();
        $endOfLastMonth = $startOfLastMonth->copy()->endOfMonth();

        $startOfLastMonthUtc = $startOfLastMonth->setTimezone('UTC');
        $endOfLastMonthUtc = $endOfLastMonth->setTimezone('UTC');

        return $this->hasMany(ConversationMessage::class, 'operator_id')
            ->where('created_at', '>=', $startOfLastMonthUtc)
            ->where('created_at', '<=', $endOfLastMonthUtc)
            ->where('operator_message_type', null);
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
        return $this->hasOne(UserAccount::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function affiliateTracking()
    {
        return $this->hasOne(UserAffiliateTracking::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fingerprints()
    {
        return $this->hasMany(UserFingerprint::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function botMessages()
    {
        return $this->hasMany(BotMessage::class, 'bot_id');
    }

    public function getExcludeFromMassMessages()
    {
        return $this->exclude_from_mass_messages;
    }
}
