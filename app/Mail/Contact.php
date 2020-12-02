<?php

namespace App\Mail;

use App\Helpers\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public array $requestData;

    public $mainColor;
    public $secondaryColor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $requestData) {

        $this->requestData = $requestData;

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
        return $this->view('emails.contact')
            ->from($this->requestData['email'])
            ->subject( $this->requestData['subject'])
            ->replyTo($this->requestData['email']);
    }
}
