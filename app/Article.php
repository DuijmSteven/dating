<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * @package App
 */
class Article extends Model
{
    public $fillable = [
        'title',
        'body',
        'status'
    ];

    public static $statuses = [
        0 => 'private',
        1 => 'public'
    ];
}
