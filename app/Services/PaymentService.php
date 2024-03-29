<?php

namespace App\Services;

use App\Creditpack;
use App\Interfaces\PaymentProvider;
use App\Payment;
use App\User;
use App\UserAccount;
use DigiWallet\Methods\Bancontact;
use DigiWallet\Transaction;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService implements PaymentProvider
{
    /**
     * PaymentService constructor.
     */
    public function __construct()
    {}

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
            case 'credit':
                $transaction = $this->creditPayment($amount, $description);
                break;
            case 'bancontact':
                $transaction = $this->bancontactPayment($amount, $description);
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
        int $creditpackId = null,
        int $discountPercentage = null
    ) {
        $user = Auth::user();

        $payment = new Payment();
        $payment->setMethod($paymentMethod);
        $payment->setDescription($description);
        $payment->setCreditpackId($creditpackId);
        $payment->setStatus(Payment::STATUS_STARTED);
        $payment->setAmount($amount);
        $payment->setTransactionId($transactionId);
        $payment->setDiscountPercentage($discountPercentage);

        $user->payments()->save($payment);
    }

    /**
     * @param string $bank
     * @param float $amount
     * @param string $description
     * @return array|mixed
     */
    public function idealPayment(string $bank, float $amount, string $description)
    {
        $startPaymentResult = Transaction::model("Ideal")
            ->outletId(config('targetpay.layoutcode'))
            ->amount($amount)
            ->description($description)
            ->returnUrl(route('payments.check'))
            ->bank($bank)
            ->test(config('targetpay.test'))
            ->start();

        return [
            'redirectUrl' => $startPaymentResult->url,
            'transaction_id' => $startPaymentResult->transactionId
        ];
    }

    /**
     * @param float $amount
     * @param string $description
     * @return array|mixed
     */
    public function paysafePayment(float $amount, string $description)
    {
        $startPaymentResult = Transaction::model("Paysafecard")
            ->outletId(config('targetpay.layoutcode'))
            ->amount($amount)
            ->description($description)
            ->returnUrl(route('payments.check'))
            ->test(config('targetpay.test'))
            ->start();

        return [
            'redirectUrl' => $startPaymentResult->url,
            'transaction_id' => $startPaymentResult->transactionId
        ];
    }

    /**
     * @param  float  $amount
     * @param  string  $description
     * @return mixed|void
     * @throws \DigiWallet\Exception
     */
    public function creditPayment(float $amount, string $description)
    {
        $startPaymentResult = Transaction::model("Creditcard")
            ->outletId(config('targetpay.layoutcode'))
            ->amount($amount)
            ->description($description)
            ->returnUrl(route('payments.check'))
            ->test(config('targetpay.test'))
            ->start();

        return [
            'redirectUrl' => $startPaymentResult->url,
            'transaction_id' => $startPaymentResult->transactionId
        ];
    }

    public function bancontactPayment(float $amount, string $description)
    {
        $startPaymentResult = Transaction::model("Bancontact")
            ->outletId(config('targetpay.layoutcode'))
            ->amount($amount)
            ->description($description)
            ->returnUrl(route('payments.check'))
            ->test(config('targetpay.test'))
            ->start();

        return [
            'redirectUrl' => $startPaymentResult->url,
            'transaction_id' => $startPaymentResult->transactionId
        ];
    }

    public function paymentCheck(
        User $peasant,
        string $paymentMethod,
        int $transactionId,
        Creditpack $creditpack,
        bool $isConversion
    ) {
        switch ($paymentMethod) {
            case 'ideal':
                $targetPay = Transaction::model("Ideal");
                break;
            case 'paysafe':
                $targetPay = Transaction::model("Paysafecard");
                break;
            case 'credit':
                $targetPay = Transaction::model("Creditcard");
                break;
            case 'bancontact':
                $targetPay = Transaction::model("Bancontact");
                break;
            default:
                throw new \Exception('Payment method invalid');
        }

        $checkPaymentResult = $targetPay
            ->outletId(config('targetpay.layoutcode'))
            ->transactionId($transactionId)
            ->test(config('targetpay.test'))
            ->check();

        if (!$checkPaymentResult->status) {
            return $check = [
                'status' => false,
                'info' => $checkPaymentResult->error
            ];
        }

        $status = $checkPaymentResult->status;

        /** @var Payment $payment */
        $payment = Payment::where('user_id', $peasant->getId())
                          ->where('transaction_id', $transactionId)
                          ->first();

        //Increase credits
        if ($status && $payment->status === Payment::STATUS_STARTED) {
            \Log::debug('Payment (ID: ' . $payment->getId() . ') - Credits before increasing them: ' . $peasant->account->credits);

            if($peasant->account()->exists()) {
                $existingCredits = $peasant->account->credits;
                $peasant->account()->update(['credits' => $existingCredits + (int) $creditpack->getCredits()]);
                $newAmountOfCredits = $existingCredits + (int) $creditpack->getCredits();
            } else {
                $account = new UserAccount(['credits' => (int) $creditpack->getCredits()]);
                $peasant->account()->save($account);
                $newAmountOfCredits = (int) $creditpack->getCredits();
            }

            \Log::debug('Payment (ID: ' . $payment->getId() . ') - Credits after increasing them: ' . $newAmountOfCredits);
        }

        //Update payment status
        $status ? $statusUpdate = Payment::STATUS_COMPLETED : $statusUpdate = Payment::STATUS_ERROR;

        Payment::where('user_id', $peasant->getId())
               ->where('transaction_id', $transactionId)
               ->update([
                   'status' => $statusUpdate,
                   'is_conversion' => $isConversion
               ]);

        return $check = [
            'status' => $status,
            'info' => ''
        ];
    }
}
