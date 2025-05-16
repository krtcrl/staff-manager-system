<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as RequestFacade;
use App\Mail\NewRequestMail;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $staff;

    public function __construct($request, $url, $staff = null)
    {
        $this->request = $request;
        $this->staff = $staff;
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

    public function via($notifiable)
    {
        // Enable both mail and database notifications
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        Log::info('Attempting to send email to: ' . $notifiable->email);
    
        return (new NewRequestMail($this->request, $this->url, $this->staff, $notifiable))
            ->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        Log::info('Storing new request notification for: ' . $notifiable->email);

        return [
            'title' => 'New Request Created',
            'message' => 'A new request has been submitted: ' . $this->request->part_number,
            'url' => $this->url,
            'type' => 'new_request',
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType('new_request'),
            'request_id' => $this->request->id
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match ($type) {
            'new_request' => 'fa-file-circle-plus',
            default => 'fa-bell'
        };
    }
}