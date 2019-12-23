<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

/**
 * Class LocationService
 * @package App\Services
 */
class UserLocationService
{
    private Position $location;

    public function __construct()
    {
        $userIp = Request::ip();
        $this->location = Location::get(/*Request::ip()*/ '82.217.122.82');
    }

    public function getUserLocation(): ?string
    {
        if (!$this->location || $this->location->countryCode !== 'NL') {
            return null;
        }

        return $this->location;
    }

    public function getUserCityName()
    {
        if (!$this->location || $this->location->countryCode !== 'NL') {
            return null;
        }

        return $this->location->cityName;
    }
}
