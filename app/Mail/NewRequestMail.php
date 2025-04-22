<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $staff;
    public $notifiable;

    public function __construct($request, $url, $staff = null, $notifiable = null)
    {
        $this->request = $request;
        $this->url = $url;
        $this->staff = $staff;
        $this->notifiable = $notifiable;
    }

    public function build()
    {
        return $this->from('joshcarillo022@gmail.com', 'ST Approval System')
                    ->replyTo('no-reply@example.com', 'No Reply')
                    ->subject('ACTION REQUIRED: New Request - ' . $this->request->part_number)
                    ->markdown('emails.new_request') // <- use markdown instead of view
                    ->with([
                        'request' => $this->request,
                        'url' => $this->url,
                        'staff' => $this->staff,
                        'notifiable' => $this->notifiable,
                    ]);
    }
}
