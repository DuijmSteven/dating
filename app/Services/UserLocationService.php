<?php

namespace App\Services;

use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Spatie\Geocoder\Geocoder;

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
            return $response['country_code'];
        } catch (RequestException $e) {
            \Log::error('Cannot get IP - ' . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                \Log::error('Cannot get IP - ' . Psr7\str($e->getResponse()));
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
            \Log::error('Cannot get IP - ' . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                \Log::error('Cannot get IP - ' . Psr7\str($e->getResponse()));
            }
        }
    }

    public function getCoordinatesForCity(string $city)
    {
        $explodedCity = explode(' (', $city);
        $cityName = trim($explodedCity[0]);
        $countryCode = explode(')', $explodedCity[1])[0];

        $client = new Client();
        $geocoder = new GeocoderService($client, $countryCode);

        return $geocoder->getCoordinatesForAddress($cityName . ', ' . $countryCode);
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
