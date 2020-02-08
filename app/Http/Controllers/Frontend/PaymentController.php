<?php

namespace App\Http\Controllers\Frontend;

use App\Creditpack;
use App\Mail\CreditsBought;
use App\Mail\Welcome;
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
                    'ideal', 'credit', 'paysafe'
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
            1,
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

    public function checkPayment()
    {
        $transactionId = session('transactionId');
        $paymentMethod = session('paymentMethod');
        $creditPackId = session('creditPackId');
        $creditPack = Creditpack::find($creditPackId);

        $check = $this->paymentProvider->paymentCheck($paymentMethod, $transactionId);

        if($check['status']) {
            $user = \Auth::user();
            $creditsBoughtEmail = (new CreditsBought($user, $creditPack))
                ->onQueue('emails');

            Mail::to($user)
                ->queue($creditsBoughtEmail);
        }

        return view('frontend.thank-you', [
            'title' => $this->buildTitleWith(trans('view_titles.payment')),
            'status' => $check['status'],
            'info' => $check['info']
        ]);
    }
}
