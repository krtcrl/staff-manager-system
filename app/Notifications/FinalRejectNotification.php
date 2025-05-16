<?php

namespace App\Notifications;

use App\Mail\FinalRejectRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;

class FinalRejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $rejectionReason;

    // Constructor to accept necessary data
    public function __construct($finalRequest, $url, $managerNumber, $rejectionReason)
    {
        $this->finalRequest = $finalRequest;
        $this->managerNumber = $managerNumber;
        $this->rejectionReason = $rejectionReason;
        $this->url = $this->convertToIpUrl($url);
    }

    /**
     * Convert Laravel URL to IP-based URL
     */
    protected function convertToIpUrl($originalUrl)
    {
        // Get the current request to extract scheme and port
        $request = RequestFacade::instance();
        $scheme = $request->getScheme();
        $port = $request->getPort();
        
        // Get server IP address
        $serverIp = $request->server('SERVER_ADDR') ?: gethostbyname(gethostname());
        
        // Parse original URL to get path
        $path = parse_url($originalUrl, PHP_URL_PATH);
        $query = parse_url($originalUrl, PHP_URL_QUERY);
        
        // Handle port in URL (skip if default port for scheme)
        $portPart = '';
        if (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)) {
            $portPart = ':' . $port;
        }
        
        // Rebuild URL with IP address
        $newUrl = "{$scheme}://{$serverIp}{$portPart}{$path}";
        
        if ($query) {
            $newUrl .= "?{$query}";
        }
        
        return $newUrl;
    }

    // Specify the delivery channels (database and mail)
    public function via($notifiable)
    {
        return ['database', 'mail']; // Send to both database and email
    }

    // Use the FinalRejectRequestMail Mailable for email format
    public function toMail($notifiable)
    {
        // Return the FinalRejectRequestMail Mailable to handle the email
        return (new FinalRejectRequestMail(
            $this->finalRequest, 
            $this->url, 
            $this->managerNumber, 
            $this->rejectionReason,
            $notifiable
        ))->to($notifiable->email); // Set the recipient email
    }

    // Define the structure of the database notification
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Final Request Rejected',
            'request_id' => $this->finalRequest->id,
            'message' => "Your final request {$this->finalRequest->unique_code} has been rejected by Final Manager {$this->managerNumber}. Reason: {$this->rejectionReason}",
            'url' => $this->url,
            'type' => 'rejected', // Mark as 'rejected'
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('rejected') // Set the icon for rejection
        ];
    }

    // This method selects the appropriate icon based on the notification type
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'rejected' => 'fa-times-circle', // Font Awesome icon for rejection (red X)
            default => 'fa-bell', // Default icon for other notification types
        };
    }
}