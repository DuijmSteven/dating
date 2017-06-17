<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 * @package App
 */
class LayoutPart extends Model
{
    public $table = 'layout_parts';

    public $fillable = [
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany('App\Module');
    }
}
