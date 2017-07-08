<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LayoutPartView
 * @package App
 */
class LayoutPartView extends Model
{
    public $table = 'layout_part_view';

    public $timestamps = false;

    public $fillable = [
        'view_id',
        'layout_part_id'
    ];
}
