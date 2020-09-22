<?php

namespace App\Mail;

use App\Creditpack;
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('emails.subjects.credits_bought'))->view('emails.credits-bought');
    }
}
