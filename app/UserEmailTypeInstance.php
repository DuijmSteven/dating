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
        'viewed_id',
        'viewer_id',
        'email_type_id',
        'email'
    ];

    public function setEmailTypeId(int $emailTypeId) {
        $this->email_type_id = $emailTypeId;
    }

    public function setViewedId(int $viewedId) {
        $this->viewed_id = $viewedId;
    }

    public function setViewerId(int $viewerId) {
        $this->viewer_id = $viewerId;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }
}
