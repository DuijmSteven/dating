<?php

namespace App\Mail;

use App\Creditpack;
use App\Helpers\EmailHelper;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditsBought extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $creditPack;
    public $transactionTotal;
    public $mainColor;
    public $secondaryColor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Creditpack $creditPack,
        $transactionTotal
    ) {
        $this->user = $user;
        $this->creditPack = $creditPack;
        $this->transactionTotal = $transactionTotal;

        $this->mainColor = EmailHelper::getSiteMainColor();
        $this->secondaryColor = EmailHelper::getSiteSecondaryColor();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $message
                ->getHeaders()
                ->addTextHeader(
                    'List-Unsubscribe',
                    '<mailto:unsubscribe@' . config('app.name') . '>'
                );
        });

        return $this
            ->subject(trans(config('app.directory_name') . '/emails.subjects.credits_bought'))
            ->view('emails.credits-bought');
    }
}
