<?php

namespace App\Mail;

use App\Helpers\EmailHelper;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PleaseComeBack extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * @var Collection
     */
    public Collection $creditpacks;

    public $mainColor;
    public $secondaryColor;
    public $discountPercentage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Collection $creditpacks
    ) {
        $this->user = $user;
        $this->creditpacks = $creditpacks;
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
        $this->withSwiftMessage(function ($message) {
            $message
                ->getHeaders()
                ->addTextHeader(
                    'List-Unsubscribe',
                    '<mailto:unsubscribe@' . config('app.name') . '>'
                );
        });

        return $this
            ->subject(trans(config('app.directory_name') . '/emails.subjects.please_come_back'))
            ->view(
                'emails.please-come-back'
            );
    }
}
