<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Interfaces\PaymentProvider;
use App\Managers\PaymentManager;
use App\Services\PaymentService;
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
     * @param PaymentManager $paymentManager
     * @param PaymentService $paymentProvider
     */
    public function __construct(PaymentManager $paymentManager, PaymentProvider $paymentProvider)
    {
        $this->paymentManager = $paymentManager;
        $this->paymentProvider = $paymentProvider;
        parent::__construct();
    }

    public function initiatePayment(Request $request)
    {
        $this->validate($request,[
            'paymentMethod' => [
                'required',
                Rule::in([
                    'ideal', 'credit', 'paysafe'
                ])
            ],
            'amount' => 'required'
        ]);

        $bank = $request->get('bank');
        $paymentMethod = $request->get('paymentMethod');
        $amount = number_format((float)$request->get('amount'), 2, '.', '');
        $description = $request->get('description') . ' credits';

        return $this->paymentProvider->initiatePayment($bank, $paymentMethod, $amount, $description);
    }
}
