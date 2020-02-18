<?php

namespace App\Http\Controllers\Admin;

use App\Creditpack;
use App\Helpers\ApplicationConstants\MetaConstants;
use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Http\Controllers\Controller;
use App\Payment;
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
        $payments = Payment::with('peasant')
            ->orderBy('created_at', 'desc')->paginate(PaginationConstants::$perPage['backend']['default']);

        $creditpacks = Creditpack::all();
        $creditpackNamePerId = [];

        foreach ($creditpacks as $creditpack) {
            $creditpackNamePerId[$creditpack->id] = $creditpack->name;
        }

        return view(
            'admin.payments.overview',
            [
                'title' => 'Payments Overview - ' . config('app.name'),
                'headingLarge' => 'Payments',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'payments' => $payments,
                'creditpackNamePerId' => $creditpackNamePerId
            ]
        );
    }
}
