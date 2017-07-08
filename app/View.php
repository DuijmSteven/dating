<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class View
 * @package App
 */
class View extends Model
{
    public $fillable = [
        'name',
        'route_name'
    ];

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

    /**
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->route_name;
    }

    /**
     * @param string $route_name
     */
    public function setRouteName(string $routeName)
    {
        $this->route_name = $routeName;
    }

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
    public function layoutParts()
    {
        return $this->belongsToMany('App\LayoutPart', 'layout_part_view');
    }
}
