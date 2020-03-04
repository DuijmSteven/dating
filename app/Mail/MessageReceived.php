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

    /** @var string */
    public $message;

    /** @var bool */
    public $hasOnlyMessage;

    /** @var bool */
    public $hasOnlyAttachment;

    /** @var bool */
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
        $this->message = $message;
        $this->hasBoth = false;
        $this->hasOnlyAttachment = false;
        $this->hasOnlyMessage = false;
        $this->carbonNow = Carbon::now('Europe/Amsterdam');

        if (strlen($message) > 0) {
            if ($hasAttachment) {
                $this->hasBoth = true;
            } else {
                $this->hasOnlyMessage = true;
            }
        } elseif ($hasAttachment) {
            $this->hasOnlyAttachment = true;
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
