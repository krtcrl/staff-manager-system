<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $url;
    public $userType;
    public $notifiable;

    public function __construct($notifiable, string $url, string $userType = 'staff')
    {
        $this->notifiable = $notifiable;
        $this->url = $url;
        $this->userType = $userType;
    }

    public function build()
    {
        return $this->to($this->notifiable->email) // Set the recipient
                   ->subject('Password Reset Request - Standard Time Approval System')
                   ->markdown('emails.password-reset');
    }
}