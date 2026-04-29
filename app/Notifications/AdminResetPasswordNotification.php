<?php
// FILE: app/Notifications/AdminResetPasswordNotification.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Admin Password Reset Request — ROSSO CORSA')
            ->greeting('Hello, Admin.')
            ->line('You are receiving this email because a password reset was requested for your admin account.')
            ->action('Reset Admin Password', $url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}