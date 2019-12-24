<?php

namespace App\Services;

use App\Interfaces\PaymentProvider;

use App\Payment;
use App\User;
use App\UserAccount;
use Illuminate\Support\Facades\Auth;
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
        $this->returnUrl = route('payments.check');
    }

    /**
     * @param  string  $bank
     * @param  string  $paymentMethod
     * @param  int  $amount
     * @param  string  $description
     * @return array|mixed
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

        return $transaction;
    }

    /**
     * @param string $paymentMethod
     * @param int $status
     * @param int $transactionId
     * @param int $amount
     * @param string|null $description
     * @param int|null $creditpackId
     * @return mixed|void
     */
    public function storePayment(
        string $paymentMethod,
        int $status,
        int $transactionId,
        int $amount,
        string $description = null,
        int $creditpackId = null
    ) {
        $user = Auth::user();

        $payment = new Payment();
        $payment->setMethod($paymentMethod);
        $payment->setDescription($description);
        $payment->setCreditpackId($creditpackId);
        $payment->setStatus(1);
        $payment->setAmount($amount);
        $payment->setTransactionId($transactionId);

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
            'transaction_id' => $transactionId
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
            'transaction_id' => $transactionId
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

    public function paymentCheck(string $paymentMethod, int $transactionId)
    {
        switch ($paymentMethod) {
            case 'ideal':
                $targetPay = new TargetPay(new IDeal());
                break;
            case 'paysafe':
                $targetPay = new TargetPay(new Paysafecard());
                break;
            default:
                throw new \Exception('Payment method invalid');
        }

        $targetPay->transaction->setTransactionId($transactionId);
        $targetPay->checkPaymentInfo();

        $status = $targetPay->transaction->getPaymentDone();

        $payment = Payment::where('user_id', Auth::user()->id)
                          ->where('transaction_id', $transactionId)
                          ->first();

        //Increase credits
        if($status && $payment->status == 1) {
            if(Auth::user()->account()->exists()) {
                $credits = Auth::user()->account->credits;
                Auth::user()->account()->update(['credits' => $credits + (int) session('credits')]);
            } else {
                $account = new UserAccount(['credits' => (int) session('credits')]);
                Auth::user()->account()->save($account);
            }
        }

        //Update payment status
        $status ? $statusUpdate = 3 : $statusUpdate = 5;

        Payment::where('user_id', Auth::user()->id)
               ->where('transaction_id', $transactionId)
               ->update(['status' => $statusUpdate]);

        return $status;
    }
}
