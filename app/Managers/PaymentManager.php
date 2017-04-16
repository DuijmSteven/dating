<?php

namespace App\Managers;

use App\DatingInterfaces\PaymentProvider;

class PaymentManager
{
    /** @var PaymentProvider $paymentProvider */
    private $paymentProvider;

    /**
     * @param PaymentProvider $paymentProvider
     */
    public function __construct(PaymentProvider $paymentProvider) {
        $this->paymentProvider = $paymentProvider;
    }


}
