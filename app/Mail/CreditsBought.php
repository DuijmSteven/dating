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

    public User $user;
    public Creditpack $creditpack;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Creditpack $creditpack
    ) {
        $this->user = $user;
        $this->creditpack = $creditpack;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('emails.credits_bought'))->view('emails.credits-bought');
    }
}
