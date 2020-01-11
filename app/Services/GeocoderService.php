<?php

namespace App\Services;

use GuzzleHttp\Client;
use Spatie\Geocoder\Geocoder;

/**
 * Class GeocoderService
 * @package App\Services
 */
class GeocoderService
{
    /**
     * @var Client
     */
    private Client $httpClient;

    /**
     * @var Geocoder
     */
    private Geocoder $geocoder;

    public function __construct(
        Client $httpClient
    ) {
        $this->httpClient = $httpClient;
        $this->geocoder = new Geocoder($this->httpClient);

        $this->geocoder->setApiKey(config('geocoder.key'));
        $this->geocoder->setCountry('nl');
    }

    /**
     * @param string $address
     * @return array
     * @throws \Spatie\Geocoder\Exceptions\CouldNotGeocode
     */
    public function getCoordinatesForAddress(string $address)
    {
        return $this->geocoder->getCoordinatesForAddress($address);
    }
}
