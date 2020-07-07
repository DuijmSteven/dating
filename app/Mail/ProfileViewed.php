<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProfileViewed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $messageRecipient;
    public $messageSender;
    public $carbonNow;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct
    (
        User $user,
        User $messageRecipient,
        User $messageSender,
        Carbon $carbonNow
    ) {
        $this->user = $user;
        $this->messageRecipient = $messageRecipient;
        $this->messageSender = $messageSender;
        $this->carbonNow = $carbonNow;
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
        
        return $this->subject(trans('emails.subjects.profile_viewed', ['username' => $this->messageSender->getUsername()]))->view('emails.profile-viewed');
    }
}
