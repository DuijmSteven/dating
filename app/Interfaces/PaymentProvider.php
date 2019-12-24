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
     * @param float $amount
     * @param string $description
     * @return mixed
     */
    public function initiatePayment(string $bank, string $paymentMethod, float $amount, string $description);

    /**
     * @param string $paymentMethod
     * @param int $status
     * @param int $transactionId
     * @param int $amount
     * @param string $description
     * @param int $creditpackId
     * @return mixed
     */
    public function storePayment(
        string $paymentMethod,
        int $status,
        int $transactionId,
        int $amount,
        string $description,
        int $creditpackId
    );

    /**
     * @param string $bank
     * @param float $amount
     * @param string $description
     * @return mixed
     */
    public function idealPayment(string $bank, float $amount, string $description);

    /**
     * @param float $amount
     * @param string $description
     * @return mixed
     */
    public function paysafePayment(float $amount, string $description);

    /**
     * @param string $bank
     * @param float $amount
     * @param string $description
     * @return mixed
     */
    public function ivrPayment(string $bank, float $amount, string $description);

    /**
     * @param  string  $paymentMethod
     * @param  int  $transactionId
     * @return mixed
     */
    public function paymentCheck(string $paymentMethod, int $transactionId);
}
