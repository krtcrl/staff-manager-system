<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $managerNumber;
    public $notifiable;

    public function __construct($request, $url, $managerNumber, $notifiable = null)
    {
        $this->request = $request;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->notifiable = $notifiable;
    }

    public function build()
    {
        return $this->from('joshcarillo022@gmail.com', 'ST Approval System')
                    ->replyTo('no-reply@example.com', 'No Reply')
                    ->subject($this->isFinal() 
                        ? 'Final Approval Required for Request' 
                        : 'Request Awaiting Your Approval')
                    ->markdown('emails.approval_request')
                    ->with([
                        'request' => $this->request,
                        'url' => $this->url,
                        'managerNumber' => $this->managerNumber,
                        'notifiable' => $this->notifiable,
                        'isFinal' => $this->isFinal()
                    ]);
    }

    protected function isFinal(): bool
    {
        return str_contains(strtolower($this->managerNumber), 'final');
    }
}
