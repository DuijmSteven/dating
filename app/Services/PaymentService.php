<?php
/**
 * Created by PhpStorm.
 * User: opalampo
 * Date: 16-4-17
 * Time: 22:00
 */

namespace App\Services;


use App\DatingInterfaces\PaymentProvider;
use TPWeb\TargetPay\TargetPay;
use TPWeb\TargetPay\Transaction\IDeal;

class PaymentService implements PaymentProvider
{
    private $returnUrl;

    public function __construct()
    {
        $this->returnUrl = route('home');
    }

    public function initiatePayment(string $bank, string $paymentMethod, int $amount, string $description)
    {
        switch ($paymentMethod) {
            case 'ideal':
                $this->idealPayment($bank, $amount, $description);
                break;
            case 'paysafe':
                $this->paysafePayment($bank, $amount, $description);
                break;
            case 'ivr':
                $this->ivrPayment($bank, $amount, $description);
                break;
            default:
                throw new \Exception('Payment method invalid');
        }
    }

    public function idealPayment(string $bank, int $amount, string $description)
    {
        $targetPay = new TargetPay(new IDeal());

        $targetPay->transaction->setBank($bank);
        $targetPay->transaction->setAmount($amount);
        $targetPay->transaction->setDescription($description);
        $targetPay->transaction->setReturnUrl($this->returnUrl);

        $targetPay->getPaymentInfo();

        $redirectUrl = $targetPay->transaction->getIdealUrl();
        $transactionId = $targetPay->transaction->getTransactionId();

        var_dump($redirectUrl);
        var_dump($transactionId);
        var_dump($this->returnUrl);
        die;
    }

    public function paysafePayment(string $bank, int $amount, string $description)
    {
        return 'pay';
    }

    public function ivrPayment(string $bank, int $amount, string $description)
    {
        return 'ivr';
    }
}