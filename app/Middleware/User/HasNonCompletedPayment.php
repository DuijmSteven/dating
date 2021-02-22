<?php

namespace App\Middleware\User;

use App\Payment;
use Closure;
use Illuminate\Support\Facades\Auth;

class HasNonCompletedPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->session()->put('transactionId', '1212');
        $request->session()->put('paymentMethod', 'ideal');
        $request->session()->put('creditPackId', '1');
        $transactionId = $request->session()->get('transactionId');

        if (null === $transactionId) {
            return redirect()->route('home');
        }

        /** @var Payment $payment */
        $payment = Payment::where('user_id', Auth::user()->getId())
            ->where('transaction_id', $transactionId)
            ->first();

        if (null === $payment) {
            return redirect()->route('home');
        }

        $paymentStatus = $payment->getStatus();

        if($paymentStatus === Payment::STATUS_COMPLETED) {
            if ($payment->getDiscountPercentage()) {
                if (Auth::user()->getDiscountPercentage()) {
                    Auth::user()->setDiscountPercentage(null);
                    Auth::user()->save();
                }
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
