<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditpack extends Model
{
    const SMALL = 1;
    const MEDIUM = 2;
    const LARGE = 3;

    public $table = 'creditpacks';

    public function getCredits()
    {
        return $this->credits;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
