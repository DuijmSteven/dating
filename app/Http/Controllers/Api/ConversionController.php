<?php

namespace App\Http\Controllers\Api;

use App\Payment;
use App\User;
use App\UserAffiliateTracking;

class ConversionController
{
    public function getPromoConversionsData()
    {
        try {
            $convertedPromoUsers = User::whereHas('affiliateTracking', function ($query) {
                $query->where('affiliate', UserAffiliateTracking::AFFILIATE_DATINGSITELIJSTPROMO);
            })
            ->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->get();

            return response()->json($convertedPromoUsers);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
}
