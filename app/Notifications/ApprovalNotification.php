<?php

namespace App\Notifications;

use App\Mail\ApprovalRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as RequestFacade;

class ApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $managerNumber;

    public function __construct($request, $url, $managerNumber)
    {
        $this->request = $request;
        $this->managerNumber = $managerNumber;
        
        // Replace Laravel URL with server IP address
        $this->url = $this->convertToIpUrl($url);
    }

    // Convert Laravel URL to IP-based URL
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

    // Send email and database notification
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Send using the custom mailable
    public function toMail($notifiable)
    {
        return (new \App\Mail\ApprovalRequestMail($this->request, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
    }

    // Store in database
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Approval Required',
            'request_id' => $this->request->id,
            'message' => "Part number {$this->request->part_number} is awaiting your approval. Manager {$this->managerNumber}, please review the request.",
            'url' => $this->url,
            'type' => 'approval_required',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-user-check'
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'approval_required' => 'fa-user-check',
            default => 'fa-bell'
        };
    }
}