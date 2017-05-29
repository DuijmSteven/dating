<?php

namespace App\Http\Controllers\Frontend;

use App\DatingInterfaces\PaymentProvider;
use App\Http\Controllers\Controller;
use App\Managers\PaymentManager;

class PaymentController extends Controller
{
    /** @var PaymentManager */
    private $paymentManager;

    /** @var PaymentProvider */
    private $paymentProvider;

    /**
     * PaymentController constructor.
     * @param PaymentManager $paymentManager
     * @param PaymentProvider $paymentProvider
     */
    public function __construct(PaymentManager $paymentManager, PaymentProvider $paymentProvider)
    {
        $this->paymentManager = $paymentManager;
        $this->paymentProvider = $paymentProvider;
    }

    public function initiatePayment()
    {
        $this->paymentProvider->initiatePayment('RABONL2U', 'ideal', 23, 'sdsdsds');
    }
}
