<?php

namespace App\Mail;

use App\Creditpack;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Exception extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $exception;
    public $siteId;
    public $siteName;
    public $siteDomain;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        ?User $user = null,
        string $siteId,
        string $siteName,
        string $siteDomain,
        \Exception $exception
    ) {
        $this->user = $user;
        $this->exception = $exception;
        $this->siteId = $siteId;
        $this->siteName = $siteName;
        $this->siteDomain = $siteDomain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(get_class($this->exception))->view('emails.exception');
    }
}
