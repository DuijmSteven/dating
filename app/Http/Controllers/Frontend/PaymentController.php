<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Managers\PaymentManager;

class PaymentController extends Controller
{
    /** @var PaymentManager */
    private $paymentManager;

    /**
     * PaymentController constructor.
     * @param PaymentManager $paymentManager
     */
    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }
}
