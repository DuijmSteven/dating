<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailType extends Model
{
    const MESSAGE_RECEIVED = 1;
    const PROFILE_VIEWED = 2;
    const WELCOME = 3;

    public $table = 'email_types';

    private $emailTypeId;

    public $timestamps = false;

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function getId() {
        return $this->id;
    }

    public function setEmailTypeId($emailTypeId) {
        $this->email_type_id = $emailTypeId;
    }

    public function setEditable(bool $editable) {
        $this->editable = $editable;
    }
}
