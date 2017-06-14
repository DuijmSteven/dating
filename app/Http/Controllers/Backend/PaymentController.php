<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ApplicationConstants\MetaConstants;
use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Http\Controllers\Controller;
use App\Payment;
use Carbon\Carbon;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Backend
 */
class PaymentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $payments = Payment::with('peasant')->orderBy('created_at', 'desc')->paginate(PaginationConstants::$perPage['backend']['default']);

        return view(
            'backend.payments.index',
            [
                'title' => 'Payments Overview - ' . MetaConstants::$siteName,
                'headingLarge' => 'Payments',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'payments' => $payments
            ]
        );
    }
}