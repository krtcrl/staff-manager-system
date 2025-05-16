<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;
use App\Mail\PasswordResetMail;

class CustomResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token,
        public bool $isManager = false
    ) {}

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
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = $this->convertToIpUrl(
            url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->email,
            ], false))
        );

        return new PasswordResetMail(
            $notifiable, // Pass the notifiable object
            $url,
            $this->isManager ? 'manager' : 'staff'
        );
    }
}