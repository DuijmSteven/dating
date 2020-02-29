<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmailTypeInstance extends TimeZonedModel
{
    public $table = 'user_email_type_instances';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receiver_id',
        'actor_id',
        'email_type_id',
        'email'
    ];

    public function setEmailTypeId(int $emailTypeId) {
        $this->email_type_id = $emailTypeId;
    }

    public function setReceiverId(int $receiverId) {
        $this->receiver_id = $receiverId;
    }

    public function setActorId(int $actorId) {
        $this->actor_id = $actorId;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }
}
