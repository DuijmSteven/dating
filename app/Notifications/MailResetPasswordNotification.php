<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class MailResetPasswordNotification extends ResetPassword
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans(config('app.directory_name') . '/emails.subjects.reset_password'))
            ->line(trans(config('app.directory_name') . '/emails.reset_password.reason'))
            ->action(trans(config('app.directory_name') . '/emails.reset_password.reset_password'), url(config('app.url').route('password.reset.final.get', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line(trans(config('app.directory_name') . '/emails.reset_password.expiration', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(trans(config('app.directory_name') . '/emails.reset_password.if_not_requested'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
