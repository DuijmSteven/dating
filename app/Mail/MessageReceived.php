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

    public $message;

    public $hasMessage;

    public $hasAttachment;

    public $hasBoth;

    /**
     * Create a new message instance.
     *
     * @return void
     * @throws \Exception
     */
    public function __construct(
        User $messageSender,
        User $messageRecipient,
        string $message,
        bool $hasAttachment
    ) {
        $this->messageSender = $messageSender;
        $this->messageRecipient = $messageRecipient;
        $this->hasBoth = false;
        $this->hasAttachment = false;
        $this->hasMessage = false;
        $this->carbonNow = Carbon::now('Europe/Amsterdam');

        if (strlen($message) > 0) {
            $this->message = $message;
            $this->hasMessage = true;

            if ($hasAttachment) {
                $this->hasBoth = true;
                $this->hasAttachment = true;
            }
        } elseif ($hasAttachment) {
            $this->hasAttachment = true;
        } else {
            throw new \Exception('An email should have a message or an attachment');
        }
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
