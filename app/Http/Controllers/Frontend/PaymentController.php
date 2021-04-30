<?php

namespace App\Http\Controllers\Frontend;

use App\Creditpack;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Mail\CreditsBought;
use App\Mail\UserBoughtCredits;
use App\Mail\Welcome;
use App\Managers\AffiliateManager;
use App\Payment;
use App\Services\UserActivityService;
use App\User;
use App\UserAffiliateTracking;
use App\UserMeta;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
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
    public function __construct(
        PaymentManager $paymentManager,
        PaymentProvider $paymentProvider,
        UserActivityService $userActivityService
    ) {
        $this->paymentManager = $paymentManager;
        $this->paymentProvider = $paymentProvider;
        parent::__construct($userActivityService);
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

        $price = (float) $creditPack->price;

        if ($this->authenticatedUser->getDiscountPercentage()) {
            $price = (1 - $this->authenticatedUser->getDiscountPercentage() / 100) * $price;
        }

        $amountWithDecimals = number_format($price, 2, '.', '');

        $transaction = $this->paymentProvider->initiatePayment($bank, $paymentMethod, $amountWithDecimals, $description);

        $this->paymentProvider->storePayment(
            $paymentMethod,
            Payment::STATUS_STARTED,
            $transaction['transaction_id'],
            $amountWithDecimals,
            $description,
            $creditPackId,
            $this->authenticatedUser->getDiscountPercentage()
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
    public function checkPayment(Request $request)
    {
        $transactionId = $request->session()->get('transactionId');
        $paymentMethod = $request->session()->get('paymentMethod');
        $creditPackId = $request->session()->get('creditPackId');
        $creditPack = Creditpack::find($creditPackId);

        $price = (float) $creditPack->price;

        /** @var User $user */
        $user = $this->authenticatedUser;

        if ($user->getDiscountPercentage()) {
            $price = (1 - $user->getDiscountPercentage() / 100) * $price;
        }

        $transactionTotal = number_format($price / 100, 2, '.', '');

        $previousCompletedPayments = Payment
            ::where('user_id', $user->getId())
            ->where('status', Payment::STATUS_COMPLETED)
            ->count();

        $isConversion = $previousCompletedPayments === 0 ? true : false;

        //get payment status from db
        $paymentStatus = Payment::where('user_id', $user->getId())
            ->where('transaction_id', $transactionId)
            ->firstOrFail()
            ->getStatus();

        //check if payment is already completed (user refreshed the thank-you page or visited it again in general)
        if($paymentStatus === Payment::STATUS_COMPLETED) {
            $user->setDiscountPercentage(null);
            $user->save();

            return redirect()->route('home');
        } else {
            $check = $this->paymentProvider->paymentCheck(
                $user,
                $paymentMethod,
                $transactionId,
                $creditPack,
                $isConversion
            );

            if ($isConversion) {
                $hoursToConversion = Carbon::now()->diffInHours($user->getCreatedAt());

                /** @var UserMeta $userMeta */
                $userMeta = $user->meta;

                $userMeta->setHoursToConversion($hoursToConversion);
                $user->meta()->save($userMeta);
            }

            if($check['status']) {
                $user->setDiscountPercentage(null);
                $user->save();

                $this->successfulPayment($user, $creditPack, $transactionId, $transactionTotal);
            }

            $check['status'] ? $status = 'success' : $status = 'fail';
            $info = $check['info'];
        }

        $request->session()->forget('transactionId');
        $request->session()->forget('paymentMethod');
        $request->session()->forget('creditPackId');

        return view('frontend.thank-you', [
            'title' => $this->buildTitleWith(trans(config('app.directory_name') . '/view_titles.payment')),
            'status' => $status,
            'info' => $info,
            'transactionId' => $transactionId,
            'transactionTotal' => $transactionTotal,
            'sku' => $creditPack->name . $creditPack->credits,
            'name' => $creditPack->name
        ]);
    }

    /**
     * @param $user
     * @param $creditPack
     * @param $transactionId
     * @param  string  $transactionTotal
     */
    public function successfulPayment(
        $user,
        $creditPack,
        $transactionId,
        string $transactionTotal
    ): void {
        if ($user->isMailable) {
            $creditsBoughtEmail = (new CreditsBought($user, $creditPack, $transactionTotal))
                ->onQueue('emails');
            Mail::to($user)
                ->queue($creditsBoughtEmail);
        }

        // email to us about the sale
        $userBoughtCreditsEmail = (new UserBoughtCredits($user, $creditPack))
            ->onQueue('emails');

        Mail::to('develyvof@gmail.com')
            ->queue($userBoughtCreditsEmail);
    }
}
