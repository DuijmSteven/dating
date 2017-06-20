<?php

namespace App\Managers;

use App\Interfaces\PaymentProvider;

/**
 * Class PaymentManager
 * @package App\Managers
 */
class PaymentManager
{
    /** @var PaymentProvider $paymentProvider */
    private $paymentProvider;

    /**
     * @param PaymentProvider $paymentProvider
     */
    public function __construct(PaymentProvider $paymentProvider)
    {
        $this->paymentProvider = $paymentProvider;
    }
}
