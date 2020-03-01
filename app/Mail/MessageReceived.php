<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $messageSender;
    public $messageRecipient;
    public $carbonNow;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $messageSender,
        User $messageRecipient
    ) {
        $this->messageSender = $messageSender;
        $this->messageRecipient = $messageRecipient;
        $this->carbonNow = Carbon::now('Europe/Amsterdam');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('emails.subjects.message_received'))->view('emails.message-received');
    }
}
