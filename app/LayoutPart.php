<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LayoutPart
 * @package App
 */
class LayoutPart extends Model
{
    public $table = 'layout_parts';
    public $timestamps = false;

    public $fillable = [
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstances()
    {
        return $this->hasMany('App\ModuleInstance');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function views()
    {
        return $this->belongsToMany('App\View', 'layout_part_view');
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}
