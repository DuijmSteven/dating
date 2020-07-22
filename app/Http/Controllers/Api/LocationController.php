<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class LocationController
 * @package App\Http\Controllers
 */
class LocationController extends Controller
{
    /**
     * @param $countryCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request, $countryCode = null)
    {
        if ($countryCode) {
            if (!\Cache::has('cities-' . $countryCode)) {
                $cities = \UserConstants::getCities($countryCode);
                \Cache::put('cities-' . $countryCode, $cities, 1000);
            }

            return response()->json([
                'cities' => \UserConstants::getCities($countryCode)
            ]);
        } else {
            $userCountryCode = 'nl';

            if ($request->user()) {
                $userCountryCode = $request->user()->meta->country;
            }

            if (!\Cache::has('cities-all-' . $userCountryCode)) {
                $cities = \UserConstants::getCities($countryCode, $userCountryCode);
                \Cache::put('cities-all-' . $userCountryCode, $cities, 1000);
            }

            return response()->json([
                'cities' => \UserConstants::getCities($countryCode, $userCountryCode)
            ]);
        }
    }
}
