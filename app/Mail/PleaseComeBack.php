<?php

namespace App\Mail;

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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans(config('app.directory_name') . '/emails.subjects.please_come_back'))
            ->view('emails.please-come-back');
    }
}
