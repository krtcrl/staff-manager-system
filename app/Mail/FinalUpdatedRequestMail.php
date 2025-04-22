<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinalUpdatedRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $notifiable;

    // Constructor to accept necessary data
    public function __construct($finalRequest, $url, $managerNumber, $notifiable)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->notifiable = $notifiable;
    }

    // Build the message
    public function build()
    {
        return $this->markdown('emails.final_updated_request')
                    ->with([
                        'finalRequest' => $this->finalRequest,
                        'url' => $this->url,
                        'managerNumber' => $this->managerNumber,
                        'notifiable' => $this->notifiable,
                    ])
                    ->subject('Final Request Updated');
    }
}
