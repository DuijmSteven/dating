<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

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
    public function getCities($countryCode)
    {
        if (!\Cache::has('cities-' . $countryCode)) {
            $cities = \UserConstants::getCities($countryCode);
            \Cache::put('cities-' . $countryCode, $cities, 1000);
        }

        return response()->json([
            'cities' => \Cache::get('cities-' . $countryCode)
        ]);
    }
}
