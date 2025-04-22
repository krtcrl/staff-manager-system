<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    // Constructor to accept necessary data
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Build the message
    public function build()
    {
        if ($this->data['type'] == 'final_approval') {
            return $this->markdown('emails.staff_notification')
                        ->with([
                            'subject' => 'Final Request Approval',
                            'message' => "Your request {$this->data['request_id']} has been approved by Final Manager {$this->data['manager_number']}",
                            'action' => 'View Request',
                            'url' => $this->data['url'],
                        ])
                        ->subject('Final Request Approval');
        }

        if ($this->data['type'] == 'approval') {
            return $this->markdown('emails.staff_notification')
                        ->with([
                            'subject' => 'Your Request Approval Status',
                            'message' => "Your request {$this->data['request_id']} has been approved by Manager {$this->data['manager_number']}",
                            'action' => 'View Request',
                            'url' => $this->data['url'],
                        ])
                        ->subject('Your Request Approval Status');
        }

        if ($this->data['type'] == 'completed') {
            return $this->markdown('emails.staff_notification')
                        ->with([
                            'subject' => 'Your Request Has Been Completed',
                            'message' => "Your request has completed the approval process and has been moved to request history.",
                            'action' => 'View Request History',
                            'url' => $this->data['url'],
                        ])
                        ->subject('Your Request Has Been Completed');
        }
    }
}
