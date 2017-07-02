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
        'name'
    ];

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
