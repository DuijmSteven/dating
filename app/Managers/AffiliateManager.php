<?php

namespace App\Managers;

use App\User;
use App\UserAffiliateTracking;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

/**
 * Class AffiliateManager
 * @package App\Managers
 */
class AffiliateManager
{
    const xparternsMediaIds = [
        'banner1',
        '143969',
        '147264',
        '145984',
        '147479',
        '147374',
        '147333',
    ];

    public function __construct(
    ) {
    }

    public function storeAffiliateTrackingInfo(int $userId, string $affiliate, $clickId, $countryCode, $mediaId = null, $publisher = null)
    {
        $affilateData = [
            'user_id' => $userId,
            'media_id' => $mediaId,
            'click_id' => $clickId,
            'affiliate' => $affiliate,
            'country_code' => $countryCode,
            'publisher' => $publisher
        ];

        /** @var UserAffiliateTracking $userAffiliateTrackingInstance */
        $userAffiliateTrackingInstance = new UserAffiliateTracking($affilateData);

        $userAffiliateTrackingInstance->save();
    }

    public function validateXpartnersLead(User $user)
    {
        $gender = $user->meta->gender === User::GENDER_MALE ? 'male' : 'female';
        $countryCode = $user->affiliateTracking->getCountryCode();
        $client = new Client();

        try {
            if (config('app.env') === 'production') {
                $response = $client->request(
                    'GET',
                    'https://mt67.net/d/?bdci=' . $user->affiliateTracking->getClickId() . '&ti=' . $user->getId() . '&pn=lead-XP-Altijdsex.nl&iv=media-' . $user->affiliateTracking->getMediaId() . '&c=' . $countryCode . '&g=' . $gender . '&cc=lead',
                    [
                        'timeout' => 4
                    ]
                );
            }

            $this->setLeadStatus(
                $user,
                UserAffiliateTracking::LEAD_STATUS_VALIDATED
            );
        } catch (RequestException $e) {
            $errorsArray = [
                'User ID: ' . $user->getId(),
                'Affiliate postback error - ' . Psr7\str($e->getRequest()),
            ];

            if ($e->hasResponse()) {
                $errorsArray[] = 'Affiliate postback error - ' . Psr7\str($e->getResponse());

            }

            \Log::error('Xpartners postback error', $errorsArray);
        }
    }

    private function setLeadStatus(User $lead, int $status)
    {
        $lead->affiliateTracking->setLeadStatus(
            $status
        );

        $lead->affiliateTracking->save();
    }

    protected function getLocationFromIp($ip)
    {
        $client = new Client();
        try {
            $response = $client->request(
                'GET',
                'http://api.ipstack.com/' . $ip . '?access_key=72a304d5560547d8825a48a9a48b13c8',
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
}
