<?php

namespace App\Services;

use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class UserLocationService
 * @package App\Services
 */
class UserLocationService
{
    public function getUserIp()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
        else { $ip = $remote; }

        return $ip;
    }

    public function getCountryCodeFromIp($ip)
    {
        $client = new Client();
        try {
            $response = $client->request(
                'GET',
                'http://api.ipstack.com/' . $ip . '?access_key=b56f13e4f12b980317694de933fd340d',
                [
                    'timeout' => 4
                ]
            );
            $response = json_decode($response->getBody(), true);
            return strtolower($response['country_code']);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                \Log::error('Cannot get IP - ...');

            }
        }
    }

    public function getLocationFromIp($ip)
    {
        $client = new Client();
        try {
            $response = $client->request(
                'GET',
                'http://api.ipstack.com/' . $ip . '?access_key=b56f13e4f12b980317694de933fd340d',
                [
                    'timeout' => 4
                ]
            );
            $response = json_decode($response->getBody(), true);
            return $response;
        } catch (RequestException $e) {
            \Log::error('Cannot get IP - ...');

            if ($e->hasResponse()) {
                \Log::error('Cannot get IP - ...');
                //\Log::error('Cannot get IP - ' . \Psr7\str($e->getResponse()));
            }
        }
    }

    public function getCoordinatesForCity(string $city)
    {
        $cityName = self::getCityNameFromCityString($city);
        $countryCode = self::getCountryCodeFromCityString($city);

        $client = new Client();
        $geocoder = new GeocoderService($client, $countryCode);

        return $geocoder->getCoordinatesForAddress($cityName . ', ' . $countryCode);
    }

    public static function getCountryCodeFromCityString(string $city)
    {
        $explodedCity = explode(' (', $city);
        return explode(')', $explodedCity[1])[0];
    }

    public static function getCityNameFromCityString(string $city)
    {
        $explodedCity = explode(' (', $city);
        return trim($explodedCity[0]);
    }

    public function getCoordinatesForUser(User $user)
    {

        if ($user->meta->city) {
            $explodedCity = explode(' (', $user->meta->city);
            $cityName = trim($explodedCity[0]);
            $countryCode = explode(')', $explodedCity[1])[0];
        } else {
            $location = $this->getLocationFromIp($this->getUserIp());
            $cityName = $location['city'];
            $countryCode = $location['country_code'];
        }

        if (!$cityName || !$countryCode) {
            $cityName = 'Amsterdam';
            $countryCode = 'nl';
        }

        $client = new Client();
        $geocoder = new GeocoderService($client, $countryCode);

        return $geocoder->getCoordinatesForAddress($cityName . ', ' . $countryCode);
    }
}
