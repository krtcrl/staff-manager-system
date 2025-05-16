<?php

namespace App\Notifications;

use App\Mail\UpdatedRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Notifications\Messages\MailMessage;

class UpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $url;
    public $managerNumber;

    public function __construct($request, $url, $managerNumber)
    {
        $this->request = $request;
        $this->managerNumber = $managerNumber;
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
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // Using the custom Mailable for the updated request
        return (new UpdatedRequestMail($this->request, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Request Updated',
            'request_id' => $this->request->id,
            'message' => "The request {$this->request->unique_code} you rejected has been updated. Manager {$this->managerNumber}, please review the updated details.",
            'url' => $this->url,
            'type' => 'updated',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-sync-alt'
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'updated' => 'fa-sync-alt',
            default => 'fa-bell'
        };
    }
}