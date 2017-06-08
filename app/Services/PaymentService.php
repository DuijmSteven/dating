<?php
/**
 * Created by PhpStorm.
 * User: opalampo
 * Date: 16-4-17
 * Time: 22:00
 */

namespace App\Services;


use App\DatingInterfaces\PaymentProvider;

use TPWeb\TargetPay\Transaction\IDeal;
use TPWeb\TargetPay\Transaction\IVR;
use TPWeb\TargetPay\Transaction\Paysafecard;

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
                $this->paysafePayment($amount, $description);
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
        $targetPay = new \TargetPay(new IDeal());

        $targetPay->transaction->setBank($bank);
        $targetPay->setAmount($amount);
        $targetPay->transaction->setDescription($description);
        $targetPay->transaction->setReturnUrl($this->returnUrl);

        $targetPay->getPaymentInfo();

        $redirectUrl = $targetPay->transaction->getIdealUrl();
        $transactionId = $targetPay->transaction->getTransactionId();
    }

    public function paysafePayment(int $amount, string $description)
    {
        $targetPay = new \TargetPay(new Paysafecard());

        $targetPay->setAmount($amount);
        $targetPay->transaction->setDescription($description);
        $targetPay->transaction->setReturnUrl($this->returnUrl);

        $targetPay->getPaymentInfo();

        $redirectUrl = $targetPay->transaction->getPaysafecardUrl();
        $transactionId = $targetPay->transaction->getTransactionId();
    }

    public function ivrPayment(string $bank, int $amount, string $description)
    {
        $targetPay = new \TargetPay(new IVR());

        $targetPay->transaction->setCountry(IVR::NETHERLAND);
        $targetPay->setAmount($amount);
        $targetPay->transaction->setMode('PC');
        $targetPay->transaction->setAdult(false);

        $targetPay->getPaymentInfo();

        $currency = $targetPay->transaction->getCurrency();
        $amount = $targetPay->getAmount();
        $serviceNumber = $targetPay->transaction->getServiceNumber();
        $payCode = $targetPay->transaction->getPayCode();
        $mode = $targetPay->transaction->getMode();
        $callCost = $targetPay->transaction->getAmountPerAction();
        $duration = $targetPay->transaction->getMode() == "PM" ? $targetPay->transaction->getDuration() . "s" : "";
    }
}