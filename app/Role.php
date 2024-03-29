<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_ADMIN = 1;
    const ROLE_PEASANT = 2;
    const ROLE_BOT = 3;
    const ROLE_OPERATOR = 4;
    const ROLE_EDITOR = 5;

    public $table = 'roles';

    public $timestamps = false;

    /**
     * Get the user that owns the meta.
     */
    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public static function roleDescriptionPerId()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_PEASANT => 'Peasant',
            self::ROLE_BOT => 'Bot',
            self::ROLE_OPERATOR => 'Operator',
            self::ROLE_EDITOR => 'Editor',
        ];
    }

    public function getId()
    {
        return $this->id;
    }
}
