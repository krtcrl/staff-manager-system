<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Request as RequestFacade;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $url;
    public $userType;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @param mixed $notifiable
     * @param string $url
     * @param string $userType
     */
    public function __construct($notifiable, string $url, string $userType = 'staff')
    {
        $this->notifiable = $notifiable;
        $this->url = $this->convertToIpUrl($url);
        $this->userType = $userType;
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
        return $this->from('joshcarillo022@gmail.com', 'ST Approval System')
                   ->replyTo('no-reply@example.com', 'No Reply')
                   ->to($this->notifiable->email)
                   ->subject('Password Reset Request - Standard Time Approval System')
                   ->markdown('emails.password-reset')
                   ->with([
                       'url' => $this->url,
                       'userType' => $this->userType,
                       'notifiable' => $this->notifiable
                   ]);
    }
}