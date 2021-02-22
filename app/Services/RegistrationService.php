<?php

namespace App\Services;

use App\UserAffiliateTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Class RegistrationService
 * @package App\Services
 */
class RegistrationService
{
    /**
     * @param Request $request
     * @param array $viewData
     * @return array
     */
    public function checkAffiliateRequestDataAndSetRegistrationViewData(Request $request, array $viewData): array
    {
        if ($request->input('keyword') || Cookie::has('registrationKeyword')) {

            if (!Cookie::has('registrationKeyword')) {
                Cookie::queue(
                    'registrationKeyword',
                    $request->input('keyword'),
                    100000
                );
            }

            if ($request->input('keyword')) {
                $registrationKeyword = $request->input('keyword');
            } else {
                $registrationKeyword = Cookie::get('registrationKeyword');
            }

            $viewData['registrationKeyword'] = $registrationKeyword;
        }

        if ($request->input('publisher') || Cookie::has('publisher')) {

            if (!Cookie::has('publisher')) {
                Cookie::queue(
                    'publisher',
                    $request->input('publisher'),
                    100000
                );
            }

            if ($request->input('publisher')) {
                $publisher = $request->input('publisher');
            } else {
                $publisher = Cookie::get('publisher');
            }

            $viewData['publisher'] = $publisher;
        }

        if (
            (
                $request->input('affiliate') &&
                $request->input('affiliate') === UserAffiliateTracking::AFFILIATE_DATECENTRALE
            )
            ||
            (
                Cookie::has('affiliate') &&
                Cookie::get('affiliate') === UserAffiliateTracking::AFFILIATE_DATECENTRALE
            )
        ) {
            if (!Cookie::has('affiliate')) {
                Cookie::queue(
                    'affiliate',
                    $request->input('affiliate'),
                    100000
                );
            }

            if ($request->input('affiliate')) {
                $affiliate = $request->input('affiliate');
            } else {
                $affiliate = Cookie::get('affiliate');
            }

            $viewData['affiliate'] = $affiliate;
        } else {
            if ($request->input('utm_campaign') || Cookie::has('mediaId')) {
                if (!Cookie::has('mediaId')) {
                    Cookie::queue(
                        'mediaId',
                        $request->input('utm_campaign'),
                        100000
                    );
                }

                if ($request->input('utm_campaign')) {
                    $mediaId = $request->input('utm_campaign');
                } else {
                    $mediaId = Cookie::get('mediaId');
                }

                $viewData['mediaId'] = $mediaId;
            }

            if ($request->input('clid') || Cookie::has('clid')) {
                if (!Cookie::has('clid')) {
                    Cookie::queue(
                        'clid',
                        $request->input('clid'),
                        100000
                    );
                }

                if ($request->input('clid')) {
                    $clid = $request->input('clid');
                } else {
                    $clid = Cookie::get('clid');
                }

                $viewData['clickId'] = $clid;
                $viewData['affiliate'] = UserAffiliateTracking::AFFILIATE_XPARTNERS;
            } elseif ($request->input('gclid') || Cookie::has('gclid')) {
                if (!Cookie::has('gclid')) {
                    Cookie::queue(
                        'gclid',
                        $request->input('gclid'),
                        100000
                    );
                }

                if ($request->input('gclid')) {
                    $glcid = $request->input('gclid');
                } else {
                    $glcid = Cookie::get('gclid');
                }

                $viewData['clickId'] = $glcid;
                $viewData['affiliate'] = UserAffiliateTracking::AFFILIATE_GOOGLE;
                $viewData['country'] = $request->input('country');
            }
        }
        return $viewData;
    }
}
