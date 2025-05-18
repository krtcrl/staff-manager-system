<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request as RequestFacade;

class StaffNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        if (isset($this->data['url'])) {
            $this->data['url'] = $this->convertToIpUrl($this->data['url']);
        }
    }

    /**
     * Convert Laravel URL to IP-based URL
     *
     * @param string $originalUrl
     * @return string
     */
    protected function convertToIpUrl($originalUrl): string
    {
        $request = RequestFacade::instance();
        $scheme = $request->getScheme();
        $port = $request->getPort();
        $serverIp = $request->server('SERVER_ADDR') ?: gethostbyname(gethostname());
        
        $path = parse_url($originalUrl, PHP_URL_PATH);
        $query = parse_url($originalUrl, PHP_URL_QUERY);
        
        $portPart = '';
        if (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)) {
            $portPart = ':' . $port;
        }
        
        $newUrl = "{$scheme}://{$serverIp}{$portPart}{$path}";
        
        if ($query) {
            $newUrl .= "?{$query}";
        }
        
        return $newUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('joshcarillo022@gmail.com', 'ST Approval System')
             ->replyTo('no-reply@example.com', 'No Reply');

        switch ($this->data['type']) {
            case 'final_approval':
                return $this->buildFinalApprovalEmail();
            case 'approval':
                return $this->buildApprovalEmail();
            case 'completed':
                return $this->buildCompletedEmail();
            default:
                return $this->buildDefaultEmail();
        }
    }

    protected function buildFinalApprovalEmail()
    {
        return $this->markdown('emails.staff_notification')
                   ->with([
                       'subject' => 'Final Request Approval',
                       'message' => "Your request {$this->data['request_id']} has been approved by Final Manager {$this->data['manager_number']}",
                       'action' => 'View Request',
                       'url' => $this->data['url'],
                   ])
                   ->subject('Final Request Approval');
    }

    protected function buildApprovalEmail()
    {
        return $this->markdown('emails.staff_notification')
                   ->with([
                       'subject' => 'Your Request Approval Status',
                       'message' => "Your request {$this->data['request_id']} has been approved by Manager {$this->data['manager_number']}",
                       'action' => 'View Request',
                       'url' => $this->data['url'],
                   ])
                   ->subject('Your Request Approval Status');
    }

    protected function buildCompletedEmail()
    {
        return $this->markdown('emails.staff_notification')
                   ->with([
                       'subject' => 'Your Request Has Been Completed',
                       'message' => "Your request has completed the approval process and has been moved to request history.",
                       'action' => 'View Request History',
                       'url' => $this->data['url'],
                   ])
                   ->subject('Your Request Has Been Completed');
    }

    protected function buildDefaultEmail()
    {
        return $this->markdown('emails.staff_notification')
                   ->with([
                       'subject' => $this->data['subject'] ?? 'Notification',
                       'message' => $this->data['message'] ?? 'You have a new notification',
                       'action' => $this->data['action'] ?? 'View Details',
                       'url' => $this->data['url'] ?? url('/'),
                   ])
                   ->subject($this->data['subject'] ?? 'Notification');
    }
}