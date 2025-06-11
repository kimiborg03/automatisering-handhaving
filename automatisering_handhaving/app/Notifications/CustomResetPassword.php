<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
    // function to send reset password email
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', ['token' => $this->token], false));
        return (new MailMessage)
            ->subject('Wachtwoord resetten')
            ->greeting('Hallo!')
            ->line('Je ontvangt deze e-mail omdat we een verzoek hebben ontvangen om je wachtwoord te resetten.')
            ->action('Reset wachtwoord', $url)
            ->line('Als je geen wachtwoord reset hebt aangevraagd, hoef je verder niets te doen.')
            ->salutation('');
    }
}