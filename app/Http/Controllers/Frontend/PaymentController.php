<?php

namespace App\Http\Controllers\Frontend;

use App\Interfaces\PaymentProvider;
use App\Managers\PaymentManager;
use App\Services\PaymentService;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Frontend
 */
class PaymentController extends FrontendController
{
    /** @var PaymentManager */
    private $paymentManager;

    /** @var PaymentService */
    private $paymentProvider;

    /**
     * PaymentController constructor.
     * @param PaymentManager $paymentManager
     * @param PaymentService $paymentProvider
     */
    public function __construct(PaymentManager $paymentManager, PaymentProvider $paymentProvider)
    {
        $this->paymentManager = $paymentManager;
        $this->paymentProvider = $paymentProvider;
        parent::__construct();
    }

    public function initiatePayment()
    {
        $this->paymentProvider->initiatePayment('RABONL2U', 'ideal', 1, 'sdsdsds');
    }
}
