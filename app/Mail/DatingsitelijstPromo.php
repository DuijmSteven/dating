<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DatingsitelijstPromo extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $mainColor;
    public $secondaryColor;

    public $altijdsexMainColor;
    public $altijdsexSecondaryColor;

    public $liefdesdateMainColor;
    public $liefdesdateSecondaryColor;

    public $sweetalkMainColor;
    public $sweetalkSecondaryColor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->mainColor = '#fff';
        $this->secondaryColor = '#009696';
        $this->tertiaryColor = '#c80d4e';

        $this->altijdsexMainColor = '#312c2c';
        $this->altijdsexSecondaryColor = '#ce5338';

        $this->liefdesdateMainColor = '#393939';
        $this->liefdesdateSecondaryColor = '#ce5a5a';

        $this->sweetalkMainColor = '#111';
        $this->sweetalkSecondaryColor = '#910432';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Probeer deze datingsites eens!')
            ->view('emails.datingsitelijst-promo');
    }
}
