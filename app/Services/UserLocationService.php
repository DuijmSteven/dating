<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

/**
 * Class UserLocationService
 * @package App\Services
 */
class UserLocationService
{
    private $location;

    public function __construct()
    {
        $userIp = Request::ip();
        //$userIp = '82.217.122.82';

        if ($userIp && Location::get($userIp)) {
            $this->location = Location::get($userIp);
            //$this->location = Location::get('82.217.122.82');
        }
    }

    public function getUserLocation(): ?string
    {
        if (!isset($this->location) || !$this->location || $this->location->countryCode !== 'NL') {
            return null;
        }

        return $this->location;
    }

    public function getUserCityName()
    {
        if (!isset($this->location) || !$this->location || $this->location->countryCode !== 'NL') {
            return null;
        }

        return $this->location->cityName;
    }
}
