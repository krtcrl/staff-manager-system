<?php

namespace App\Notifications;

use App\Mail\FinalUpdatedRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Notifications\Messages\MailMessage;

class FinalUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $finalRequest;
    public $url;
    public $managerNumber;

    public function __construct($finalRequest, $url, $managerNumber)
    {
        $this->finalRequest = $finalRequest;
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
        return ['mail', 'database'];  // Added mail channel
    }

    public function toMail($notifiable)
    {
        // Using the custom Mailable for the updated final request
        return (new FinalUpdatedRequestMail($this->finalRequest, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Final Request Updated',
            'final_request_id' => $this->finalRequest->id,
            'message' => "The final request {$this->finalRequest->unique_code} you rejected has been updated. Manager {$this->managerNumber}, please review the updated details.",
            'url' => $this->url,
            'type' => 'updated',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-sync-alt',
        ];
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'updated' => 'fa-sync-alt',
            default => 'fa-bell',
        };
    }
}