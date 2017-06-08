<?php

namespace App\DatingInterfaces;

interface PaymentProvider
{
    public function initiatePayment(string $bank, string $paymentMethod, int $amount, string $description);

    public function idealPayment(string $bank, int $amount, string $description);

    public function paysafePayment(int $amount, string $description);

    public function ivrPayment(string $bank, int $amount, string $description);
}