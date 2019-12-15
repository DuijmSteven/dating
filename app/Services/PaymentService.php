<?php

namespace App\Services;

use App\Interfaces\PaymentProvider;

use App\Payment;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use TPWeb\TargetPay\TargetPay;
use TPWeb\TargetPay\Transaction\IDeal;
use TPWeb\TargetPay\Transaction\IVR;
use TPWeb\TargetPay\Transaction\Paysafecard;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService implements PaymentProvider
{
    private $returnUrl;

    /**
     * PaymentService constructor.
     */
    public function __construct()
    {
        $this->returnUrl = route('home');
    }

    /**
     * @param string $bank
     * @param string $paymentMethod
     * @param int $amount
     * @param string $description
     * @throws \Exception
     */
    public function initiatePayment(string $bank, string $paymentMethod, float $amount, string $description)
    {
        switch ($paymentMethod) {
            case 'ideal':
                $transaction = $this->idealPayment($bank, $amount, $description);
                break;
            case 'paysafe':
                $transaction = $this->paysafePayment($amount, $description);
                break;
            case 'ivr':
                $this->ivrPayment($bank, $amount, $description);
                break;
            default:
                throw new \Exception('Payment method invalid');
        }

        $this->storePayment($paymentMethod, $description, 1, $transaction['transactionId']);

        return redirect()->away($transaction['redirectUrl']);
    }

    /**
     * @param  string  $paymentMethod
     * @param  string  $description
     * @param  int  $status
     * @param  int  $transactionId
     * @return mixed|void
     */
    public function storePayment(string $paymentMethod, string $description, int $status, int $transactionId)
    {
        //TODO select authenticated user (now there is a bug with the navbar) and implement payments status
        $user = User::find(1);

        $payment = new Payment();
        $payment->method = $paymentMethod;
        $payment->description = $description;
        $payment->status = 1;
        $payment->transactionId = $transactionId;

        $user->payments()->save($payment);
    }

    /**
     * @param string $bank
     * @param int $amount
     * @param string $description
     */
    public function idealPayment(string $bank, float $amount, string $description)
    {
        /** @var TargetPay $targetPay */
        $targetPay = new \TargetPay(new IDeal());

        /** @var IDeal $transaction */
        $transaction = $targetPay->transaction;

        $transaction->setBank($bank);
        $targetPay->setAmount($amount);
        $transaction->setDescription($description);
        $transaction->setReturnUrl($this->returnUrl);

        $targetPay->getPaymentInfo();

        $redirectUrl = $transaction->getIdealUrl();
        $transactionId = $transaction->getTransactionId();

        return [
            'redirectUrl' => $redirectUrl,
            'transactionId' => $transactionId
        ];
    }

    /**
     * @param int $amount
     * @param string $description
     */
    public function paysafePayment(float $amount, string $description)
    {
        /** @var TargetPay $targetPay */
        $targetPay = new \TargetPay(new Paysafecard());

        /** @var Paysafecard $transaction */
        $transaction = $targetPay->transaction;

        $targetPay->setAmount($amount);
        $transaction->setDescription($description);
        $transaction->setReturnUrl($this->returnUrl);

        $targetPay->getPaymentInfo();

        $redirectUrl = $transaction->getPaysafecardUrl();
        $transactionId = $transaction->getTransactionId();

        return [
            'redirectUrl' => $redirectUrl,
            'transactionId' => $transactionId
        ];
    }

    /**
     * @param string $bank
     * @param int $amount
     * @param string $description
     */
    public function ivrPayment(string $bank, float $amount, string $description)
    {
        /** @var TargetPay $targetPay */
        $targetPay = new \TargetPay(new IVR());

        /** @var IVR $transaction */
        $transaction = $targetPay->transaction;

        $transaction->setCountry(IVR::NETHERLAND);
        $targetPay->setAmount($amount);
        $transaction->setMode('PC');
        $transaction->setAdult(false);

        $targetPay->getPaymentInfo();

        $currency = $transaction->getCurrency();
        $amount = $targetPay->getAmount();
        $serviceNumber = $transaction->getServiceNumber();
        $payCode = $transaction->getPayCode();
        $mode = $transaction->getMode();
        $callCost = $transaction->getAmountPerAction();
        $duration = $transaction->getMode() == "PM" ? $transaction->getDuration() . "s" : "";
    }
}
