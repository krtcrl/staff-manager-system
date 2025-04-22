<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdatedRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $managerNumber;
    public $notifiable;

    // Constructor to accept necessary data
    public function __construct($request, $url, $managerNumber, $notifiable)
    {
        $this->request = $request;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->notifiable = $notifiable;
    }

    // Build the message
    public function build()
    {
        return $this->markdown('emails.updated_request')
                    ->with([
                        'request' => $this->request,
                        'url' => $this->url,
                        'managerNumber' => $this->managerNumber,
                        'notifiable' => $this->notifiable,
                    ])
                    ->subject('Updated Request Notification');
    }
}
