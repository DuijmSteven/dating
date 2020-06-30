<?php

namespace App\Services;

use App\User;
use App\UserMeta;
use GuzzleHttp\Client;
use Spatie\Geocoder\Geocoder;

/**
 * Class ProbabilityService
 * @package App\Services
 */
class ProbabilityService
{
    public static function getTrueAPercentageOfTheTime($percentage = 50)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \Exception('Percentage out of bounds 0-100');
        }

        $arrayOfPossibilities = [];
        $arrayOfValidPossibilities = [];

        for ($i = 1; $i <= 100; $i++) {
            $arrayOfPossibilities[] = $i;

            if ($i <= $percentage) {
                $arrayOfValidPossibilities[] = $i;
            }
        }

        $selectedKey = rand(0, 99);

        if (in_array($arrayOfPossibilities[$selectedKey], $arrayOfValidPossibilities)) {
            return true;
        }

        return false;
    }
}
