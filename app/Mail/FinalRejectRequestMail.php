<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinalRejectRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $rejectionReason;
    public $notifiable;

    public function __construct($finalRequest, $url, $managerNumber, $rejectionReason, $notifiable = null)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->rejectionReason = $rejectionReason;
        $this->notifiable = $notifiable;
    }

    public function build()
    {
        return $this->from('joshcarillo022@gmail.com', 'ST Approval System')
                    ->replyTo('no-reply@example.com', 'No Reply')
                    ->subject('Final Request Rejected')
                    ->markdown('emails.final_reject_request')
                    ->with([
                        'finalRequest' => $this->finalRequest,
                        'url' => $this->url,
                        'managerNumber' => $this->managerNumber,
                        'rejectionReason' => $this->rejectionReason,
                        'notifiable' => $this->notifiable
                    ]);
    }
}
