<?php

namespace App\Interfaces;

/**
 * Interface PaymentProvider
 * @package App\Interfaces
 */
interface PaymentProvider
{
    /**
     * @param string $bank
     * @param string $paymentMethod
     * @param int $amount
     * @param string $description
     * @return mixed
     */
    public function initiatePayment(string $bank, string $paymentMethod, int $amount, string $description);

    /**
     * @param string $bank
     * @param int $amount
     * @param string $description
     * @return mixed
     */
    public function idealPayment(string $bank, int $amount, string $description);

    /**
     * @param int $amount
     * @param string $description
     * @return mixed
     */
    public function paysafePayment(int $amount, string $description);

    /**
     * @param string $bank
     * @param int $amount
     * @param string $description
     * @return mixed
     */
    public function ivrPayment(string $bank, int $amount, string $description);
}
