<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Module
 * @package App
 */
class Module extends Model
{
    public $table = 'modules';

    public $fillable = [
        'name',
        'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function layoutParts()
    {
        return $this->belongsToMany('App\LayoutPart')->withPivot('priority');
    }
}
