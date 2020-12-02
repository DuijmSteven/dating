<?php

namespace App\Mail;

use App\Creditpack;
use App\Helpers\EmailHelper;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserBoughtCredits extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $creditPack;
    public $mainColor;
    public $secondaryColor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Creditpack $creditPack
    ) {
        $this->user = $user;
        $this->creditPack = $creditPack;
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
        return $this
            ->subject(trans(config('app.directory_name') . '/emails.subjects.credits_bought'))
            ->view('emails.user-bought-credits');
    }
}
