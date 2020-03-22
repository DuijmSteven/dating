<?php

namespace App\Http\Controllers\Frontend;

use App\Creditpack;
use App\Mail\CreditsBought;
use App\Mail\UserBoughtCredits;
use App\Mail\Welcome;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use App\Interfaces\PaymentProvider;
use App\Managers\PaymentManager;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Frontend
 */
class PaymentController extends FrontendController
{
    /** @var PaymentManager */
    private $paymentManager;

    /** @var PaymentService */
    private $paymentProvider;

    /**
     * PaymentController constructor.
     * @param  PaymentManager  $paymentManager
     * @param  PaymentProvider  $paymentProvider
     */
    public function __construct(PaymentManager $paymentManager, PaymentProvider $paymentProvider)
    {
        $this->paymentManager = $paymentManager;
        $this->paymentProvider = $paymentProvider;
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function makePayment(Request $request)
    {
        $this->validate($request,[
            'paymentMethod' => [
                'required',
                Rule::in([
                    'ideal', 'credit', 'paysafe', 'bancontact'
                ])
            ],
        ]);

        $bank = $request->get('bank');
        $paymentMethod = $request->get('paymentMethod');
        $creditPackId = (int) $request->get('creditpack_id');

        /** @var Creditpack $creditPack */
        $creditPack = Creditpack::find($creditPackId);

        $description = $creditPack->getDescription();

        $amountWithDecimals = number_format((float) $creditPack->price, 2, '.', '');

        $transaction = $this->paymentProvider->initiatePayment($bank, $paymentMethod, $amountWithDecimals, $description);

        $this->paymentProvider->storePayment(
            $paymentMethod,
            Payment::STATUS_STARTED,
            $transaction['transaction_id'],
            $amountWithDecimals,
            $description,
            $creditPackId
        );

        session([
            'transactionId' => $transaction['transaction_id'],
            'paymentMethod' => $paymentMethod,
            'credits' => $creditPack->credits,
            'creditPackId' => $creditPack->id
        ]);

        return redirect()->away($transaction['redirectUrl']);
    }

    /**
     * @throws \Exception
     */
    public function checkPayment()
    {
        $transactionId = session('transactionId');
        $paymentMethod = session('paymentMethod');
        $creditPackId = session('creditPackId');
        $creditPack = Creditpack::find($creditPackId);

        //get payment status from db
        $paymentStatus = Payment::where('user_id', \Auth::user()->id)
            ->where('transaction_id', $transactionId)
            ->firstOrFail()
            ->getStatus();

        //check if payment is already completed (user refreshed the thank-you page or visited it again in general)
        if($paymentStatus == Payment::STATUS_COMPLETED) {
            return redirect()->route('home');
        } else {
            $check = $this->paymentProvider->paymentCheck(
                $this->authenticatedUser,
                $paymentMethod,
                $transactionId,
                $creditPack
            );

            if($check['status']) {
                //Get current user
                $user = \Auth::user();
                $creditsBoughtEmail = (new CreditsBought($user, $creditPack))
                    ->onQueue('emails');

                Mail::to($user)
                    ->queue($creditsBoughtEmail);

                // email to us about the sale
                $userBoughtCreditsEmail = (new UserBoughtCredits($user, $creditPack))
                    ->onQueue('emails');

                Mail::to('develyvof@gmail.com')
                    ->queue($userBoughtCreditsEmail);
            }

            $check['status'] ? $status = 'success' : $status = 'fail';
            $info = $check['info'];
        }

        return view('frontend.thank-you', [
            'title' => $this->buildTitleWith(trans('view_titles.payment')),
            'status' => $status,
            'info' => $info,
            'transactionId' => $transactionId,
            'transactionTotal' => number_format((float) $creditPack->price/100, 2, '.', ''),
            'sku' => $creditPack->name . $creditPack->credits,
            'name' => $creditPack->name
        ]);
    }
}
