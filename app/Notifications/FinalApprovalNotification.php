<?php

namespace App\Notifications;

use App\Mail\FinalApprovalRequestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request as RequestFacade;

class FinalApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $finalRequest;
    public $url;
    public $managerNumber;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\FinalRequest  $finalRequest
     * @param  string  $url
     * @param  int  $managerNumber
     * @return void
     */
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

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];  // Send email and save to database
    }

    /**
     * Send the email using the custom Mailable.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Using the custom Mailable for final approval
        return (new FinalApprovalRequestMail($this->finalRequest, $this->url, $this->managerNumber, $notifiable))
                    ->to($notifiable->email);
    }

    /**
     * Build the database notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // Convert the database notification URL to use IP as well
        $dbUrl = $this->convertToIpUrl(url("/staff/final/{$this->finalRequest->unique_code}"));

        return [
            'title' => 'Final Approval Completed',
            'request_id' => $this->finalRequest->unique_code,
            'message' => "Your final approval request {$this->finalRequest->unique_code} has been approved by Manager {$this->managerNumber}.",
            'url' => $dbUrl,
            'type' => 'final_approval_completed',
            'timestamp' => now()->toDateTimeString(),
            'icon' => 'fa-thumbs-up'
        ];
    }

    /**
     * Get the appropriate icon for the notification type.
     *
     * @param  string  $type
     * @return string
     */
    protected function getIconForType(string $type): string
    {
        return match($type) {
            'final_approval_completed' => 'fa-thumbs-up',
            default => 'fa-bell',
        };
    }
}