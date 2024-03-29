<?php

namespace App\Console\Commands;

use App\Interfaces\PaymentProvider;
use App\Mail\CreditsBought;
use App\Mail\UserBoughtCredits;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckRecentStartedPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-recent-started';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if recent started Digiwallet payments are completed and changes their status if they are';
    /**
     * @var PaymentProvider
     */
    private PaymentProvider $paymentProvider;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PaymentProvider $paymentProvider)
    {
        parent::__construct();
        $this->paymentProvider = $paymentProvider;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $recentStartedPayments = Payment::with(['peasant', 'creditpack', 'peasant.account'])
            ->where('status', Payment::STATUS_STARTED)
            ->where('created_at', '>=', Carbon::now()->subMinutes(20))
            ->where('created_at', '<=', Carbon::now()->subMinutes(3))
            ->get();

        /** @var Payment $payment */
        foreach ($recentStartedPayments as $payment) {
            \Log::debug('Payment ID: ' . $payment->id);

            $previousCompletedPaymentsOfPeasant = Payment
                ::where('user_id', $payment->peasant->getId())
                ->where('status', Payment::STATUS_COMPLETED)
                ->count();

            $isConversion = $previousCompletedPaymentsOfPeasant === 0 ? true : false;

            $check = $this->paymentProvider->paymentCheck(
                $payment->peasant,
                $payment->getMethod(),
                $payment->getTransactionId(),
                $payment->creditpack,
                $isConversion
            );

            if($check['status']) {
                \Log::debug('Payment (ID: ' . $payment->getId() . ') of user ' . $payment->peasant->getUsername() . ' (ID: ' . $payment->peasant->getId() . ') was found to have remained on status started and was changed to status completed');

                $price = (float) $payment->creditpack->price;
                $peasant = $payment->peasant;

                $peasant->setDiscountPercentage(null);
                $peasant->save();

                if ($peasant->getDiscountPercentage()) {
                    $price = (1 - $peasant->getDiscountPercentage() / 100) * $price;
                }

                $transactionTotal = number_format($price / 100, 2, '.', '');

                $creditsBoughtEmail = (new CreditsBought($payment->peasant, $payment->creditpack, $transactionTotal))
                    ->onQueue('emails');

                Mail::to($payment->peasant)
                    ->queue($creditsBoughtEmail);

                // email to us about the sale
                $userBoughtCreditsEmail = (new UserBoughtCredits($payment->peasant, $payment->creditpack))
                    ->onQueue('emails');

                Mail::to('develyvof@gmail.com')
                    ->queue($userBoughtCreditsEmail);
            }

        }

//        \Log::debug('Executing payments:check-recent-started is done.');
    }
}
