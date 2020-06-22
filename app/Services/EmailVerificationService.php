<?php

namespace App\Services;

use GuzzleHttp\Client;
use Spatie\Geocoder\Geocoder;

/**
 * Class EmailVerificationService
 * @package App\Services
 */
class EmailVerificationService
{
    const VALID_EMAIL_RESULT = 'deliverable';
    const INVALID_EMAIL_RESULT = 'undeliverable';
    const ERROR_RESULT = 'error';

    /** @var string */
    private $apiKey;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(
        Client $httpClient
    ) {
        $this->httpClient = $httpClient;
        $this->setApiKey(config('the_checker.api_key'));
    }

    private function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function verifySingleEmail(string $email)
    {
        try {
            $response = $this->httpClient->get(
                config('the_checker.api_base_url') .
                config('the_checker.api_single_verification_endpoint') .
                '?email=' . $email .
                '&api_key=' . config('the_checker.api_key')
            );

            $responseContents = json_decode($response->getBody()->getContents(), true);

            if ($responseContents && isset($responseContents['result'])) {
                return $responseContents['result'];
            }

            return null;
        } catch (\Exception $exception) {
            return 'error';
        }
    }
}
