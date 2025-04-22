<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $notifiable;

    // Constructor to accept necessary data
    public function __construct($password, $notifiable)
    {
        $this->password = $password;
        $this->notifiable = $notifiable;
    }

    // Build the message
    public function build()
    {
        return $this->markdown('emails.staff_account_created')
                    ->with([
                        'password' => $this->password,
                        'notifiable' => $this->notifiable,
                    ])
                    ->subject('Your Staff Account Has Been Created');
    }
}
