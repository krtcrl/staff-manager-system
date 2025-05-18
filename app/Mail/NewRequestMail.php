<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request as RequestFacade;

class NewRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $staff;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @param mixed $request
     * @param string $url
     * @param mixed $staff
     * @param mixed $notifiable
     */
    public function __construct($request, $url, $staff = null, $notifiable = null)
    {
        $this->request = $request;
        $this->url = $this->convertToIpUrl($url);
        $this->staff = $staff;
        $this->notifiable = $notifiable;
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
                   ->subject('ACTION REQUIRED: New Request - ' . $this->request->part_number)
                   ->markdown('emails.new_request')
                   ->with([
                       'request' => $this->request,
                       'url' => $this->url,
                       'staff' => $this->staff,
                       'notifiable' => $this->notifiable
                   ]);
    }
}