<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinalApprovalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\FinalRequest  $finalRequest
     * @param  string  $url
     * @param  int  $managerNumber
     * @param  mixed  $notifiable
     * @return void
     */
    public function __construct($finalRequest, $url, $managerNumber, $notifiable = null)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $url;
        $this->managerNumber = $managerNumber;
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('joshcarillo022@gmail.com', 'ST Approval System')
                    ->replyTo('no-reply@example.com', 'No Reply')
                    ->subject($this->isFinal() 
                        ? 'Final Approval Required for Request' 
                        : 'Request Awaiting Your Final Approval')
                    ->markdown('emails.final_approval_request')  // Use your markdown Blade view
                    ->with([
                        'finalRequest' => $this->finalRequest,
                        'url' => $this->url,
                        'managerNumber' => $this->managerNumber,
                        'notifiable' => $this->notifiable,
                        'isFinal' => $this->isFinal()
                    ]);
    }

    /**
     * Determine if this is the final approval stage.
     *
     * @return bool
     */
    protected function isFinal(): bool
    {
        return str_contains(strtolower($this->managerNumber), 'final');
    }
}
