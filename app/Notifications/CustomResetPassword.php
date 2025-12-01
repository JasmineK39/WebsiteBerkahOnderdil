<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    protected $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password')
            ->line('Anda menerima email ini karena kami menerima permintaan untuk mereset password akun Anda.')
            ->action('Reset Password', $this->url)
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }
}
