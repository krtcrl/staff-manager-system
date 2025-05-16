<?php

namespace App\Notifications;

use App\Mail\StaffNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Log;

class StaffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
        // Convert URL to IP-based if it exists in the data
        if (isset($this->data['url'])) {
            $this->data['url'] = $this->convertToIpUrl($this->data['url']);
        }
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

    // Add 'mail' channel to send an email as well as save to the database
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Define the email format
    public function toMail($notifiable)
    {
        Log::info('Sending staff email to: ' . $notifiable->email);
    
        // Use the custom Mailable for staff notifications
        return (new StaffNotificationMail($this->data))
                    ->to($notifiable->email);
    }

    // Define how the database notification should be stored
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'url' => $this->data['url'] ?? null,
            'type' => $this->data['type'] ?? 'general',
            'timestamp' => now()->toDateTimeString(),
            'icon' => $this->getIconForType($this->data['type'] ?? 'general')
        ];
    }

    // Determine the icon based on the notification type
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'approval' => 'fa-thumbs-up',
            'progress' => 'fa-arrow-right',
            'final_approval' => 'fa-file-signature',
            'completion' => 'fa-check-circle',
            default => 'fa-bell'
        };
    }
}