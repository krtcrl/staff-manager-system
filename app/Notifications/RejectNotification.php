<?php

namespace App\Notifications;

use App\Mail\RejectRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;

class RejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $managerNumber;
    public $rejectionReason;

    public function __construct($request, $url, $managerNumber, $rejectionReason)
    {
        $this->request = $request;
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

    // Add email and database channels
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Use the RejectRequestMail Mailable for email format
    public function toMail($notifiable)
    {
        return (new RejectRequestMail(
            $this->request, 
            $this->url, 
            $this->managerNumber, 
            $this->rejectionReason,
            $notifiable
        ))->to($notifiable->email);
    }

    // Define database notification format
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Request Rejected',
            'request_id' => $this->request->id,
            'message' => "Your request {$this->request->unique_code} has been rejected by Manager {$this->managerNumber}. Reason: {$this->rejectionReason}",
            'url' => $this->url,
            'type' => 'rejected',
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('rejected'),
        ];
    }

    // Dynamically choose the icon based on notification type
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'rejected' => 'fa-times-circle',
            default => 'fa-bell',
        };
    }
}