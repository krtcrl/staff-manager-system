<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\PasswordResetMail;

class CustomResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token,
        public bool $isManager = false
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        return new PasswordResetMail(
            $notifiable, // Pass the notifiable object
            $url,
            $this->isManager ? 'manager' : 'staff'
        );
    }
}