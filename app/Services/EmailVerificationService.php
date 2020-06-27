<?php

namespace App\Services;

use App\User;
use App\UserMeta;
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
    const RISKY_EMAIL_RESULT = 'risky';
    const UNKNOWN_EMAIL_RESULT = 'unknown';
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

    private function setApiKey($apiKey)
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

            \Log::debug('context', $responseContents);
            \Log::debug($responseContents['result']);


            if ($responseContents && isset($responseContents['result'])) {

                return $responseContents['result'];
            }

            return null;
        } catch (\Exception $exception) {
            \Log::debug('error');

            return 'error';
        }
    }

    public function setUserMetaFromVerificationResult(User $user, $result)
    {
        $user->meta->setEmailVerified(
            UserMeta::EMAIL_VERIFIED_OTHER
        );

        if ($result === EmailVerificationService::VALID_EMAIL_RESULT) {
            $user->meta->setEmailVerified(
                UserMeta::EMAIL_VERIFIED_DELIVERABLE
            );
        } elseif ($result === EmailVerificationService::INVALID_EMAIL_RESULT) {
            $user->meta->setEmailVerified(
                UserMeta::EMAIL_VERIFIED_UNDELIVERABLE
            );
        } elseif ($result === EmailVerificationService::RISKY_EMAIL_RESULT) {
            $user->meta->setEmailVerified(
                UserMeta::EMAIL_VERIFIED_RISKY
            );
        } elseif ($result === EmailVerificationService::UNKNOWN_EMAIL_RESULT) {
            $user->meta->setEmailVerified(
                UserMeta::EMAIL_VERIFIED_UNKNOWN
            );
        } elseif ($result === EmailVerificationService::ERROR_RESULT) {
            $user->meta->setEmailVerified(
                UserMeta::EMAIL_VERIFIED_FAILED
            );
        }

        $user->meta->save();
    }
}
