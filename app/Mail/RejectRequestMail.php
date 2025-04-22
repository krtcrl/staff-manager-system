<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $managerNumber;
    public $rejectionReason;
    public $notifiable;

    public function __construct($request, $url, $managerNumber, $rejectionReason, $notifiable = null)
    {
        $this->request = $request;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->rejectionReason = $rejectionReason;
        $this->notifiable = $notifiable;
    }

    public function build()
    {
        return $this->from('joshcarillo022@gmail.com', 'ST Approval System')
                    ->replyTo('no-reply@example.com', 'No Reply')
                    ->subject('Request Rejected by Manager')
                    ->markdown('emails.reject_request')
                    ->with([
                        'request' => $this->request,
                        'url' => $this->url,
                        'managerNumber' => $this->managerNumber,
                        'rejectionReason' => $this->rejectionReason,
                        'notifiable' => $this->notifiable
                    ]);
    }
}
