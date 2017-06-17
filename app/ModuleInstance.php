<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 * @package App
 */
class ModuleInstance extends Model
{
    public $table = 'module_instances';

    public $fillable = [
        'layout_location',
        'order'
    ];
}
