<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $messageSender;
    public $messageRecipient;
    public $carbonNow;

    public $messageBody;

    public $hasMessage;

    public $hasAttachment;

    public $hasBoth;

    /**
     * Create a new message instance.
     *
     * @param User $messageSender
     * @param User $messageRecipient
     * @param string $messageBody
     * @param bool $hasAttachment
     * @throws \Exception
     */
    public function __construct(
        User $messageSender,
        User $messageRecipient,
        ?string $messageBody,
        bool $hasAttachment
    ) {
        $this->messageSender = $messageSender;
        $this->messageRecipient = $messageRecipient;
        $this->hasBoth = false;
        $this->hasAttachment = false;
        $this->hasMessage = false;
        $this->carbonNow = Carbon::now('Europe/Amsterdam');

        if (strlen($messageBody) > 0) {
            $this->messageBody = $messageBody;
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
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                ->addTextHeader('List-Unsubscribe', '<mailto:unsubscribe@altijdsex.nl>');
        });

        return $this->subject(trans(config('app.directory_name') . '/emails.subjects.message_received'))->view('emails.message-received');
    }
}
