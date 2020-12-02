<?php

namespace App\Mail;

use App\Creditpack;
use App\Helpers\EmailHelper;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
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
    public $requestUrl;
    public $exceptionMessage;
    public $exceptionTrace;
    public $exceptionClass;
    public $mainColor;
    public $secondaryColor;

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
        ?string $exceptionMessage,
        ?string $exceptionTrace,
        ?string $exceptionClass,
        ?string $requestUrl
    ) {
        $this->user = $user;
        $this->siteId = $siteId;
        $this->siteName = $siteName;
        $this->siteDomain = $siteDomain;
        $this->requestUrl = $requestUrl;
        $this->exceptionMessage = $exceptionMessage;
        $this->exceptionTrace = $exceptionTrace;
        $this->exceptionClass = $exceptionClass;
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
        $subject = $this->exceptionClass ? $this->exceptionClass : 'Unknown exception';
        return $this->subject($subject)->view('emails.exception');
    }
}
