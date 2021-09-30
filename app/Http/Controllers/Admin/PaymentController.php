<?php

namespace App\Http\Controllers\Admin;

use App\Creditpack;
use App\Helpers\ApplicationConstants\MetaConstants;
use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Http\Controllers\Controller;
use App\Payment;
use App\User;
use App\UserAffiliateTracking;
use Carbon\Carbon;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Admin
 */
class PaymentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $payments = Payment::with([
            'peasant',
            'peasant.affiliateTracking',
            'peasant.payments' => function($query) {
                $query->where('status', Payment::STATUS_COMPLETED)
                    ->orderBy('created_at', 'desc');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(PaginationConstants::$perPage['backend']['default']);

        $creditpacks = Creditpack::all();
        $creditpackNamePerId = [];

        foreach ($creditpacks as $creditpack) {
            $creditpackNamePerId[$creditpack->id] = $creditpack->name;
        }

        return view(
            'admin.payments.overview',
            [
                'title' => 'Payments Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Payments',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'payments' => $payments,
                'creditpackNamePerId' => $creditpackNamePerId
            ]
        );
    }

    public function completed()
    {
        $payments = Payment::with([
            'peasant',
            'peasant.affiliateTracking',
            'peasant.payments' => function($query) {
                $query->where('status', Payment::STATUS_COMPLETED)
                    ->orderBy('created_at', 'desc');
            }
        ])
            ->where('status', Payment::STATUS_COMPLETED)
            ->orderBy('created_at', 'desc')
            ->paginate(PaginationConstants::$perPage['backend']['default']);

        $creditpacks = Creditpack::all();
        $creditpackNamePerId = [];

        foreach ($creditpacks as $creditpack) {
            $creditpackNamePerId[$creditpack->id] = $creditpack->name;
        }

        return view(
            'admin.payments.overview',
            [
                'title' => 'Completed Payments Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Payments',
                'headingSmall' => 'Completed',
                'carbonNow' => Carbon::now(),
                'payments' => $payments,
                'creditpackNamePerId' => $creditpackNamePerId
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofPeasantId(int $peasantId)
    {
        $payments = Payment::with('peasant')
            ->where('user_id', $peasantId)
            ->orderBy('created_at', 'desc')
            ->paginate(PaginationConstants::$perPage['backend']['default']);


        $peasant = User::find($peasantId);

        $creditpacks = Creditpack::all();
        $creditpackNamePerId = [];

        foreach ($creditpacks as $creditpack) {
            $creditpackNamePerId[$creditpack->id] = $creditpack->name;
        }

        return view(
            'admin.payments.overview',
            [
                'title' => 'Payments Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Payments of ' . $peasant->getUsername() . ' (ID: ' . $peasant->getId() . ')',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'payments' => $payments,
                'creditpackNamePerId' => $creditpackNamePerId
            ]
        );
    }
}
