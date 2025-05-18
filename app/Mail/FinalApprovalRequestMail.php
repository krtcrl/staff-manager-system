<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request as RequestFacade;

class FinalApprovalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\FinalRequest  $finalRequest
     * @param  string  $url
     * @param  int  $managerNumber
     * @param  mixed  $notifiable
     * @return void
     */
    public function __construct($finalRequest, $url, $managerNumber, $notifiable = null)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $this->convertToIpUrl($url);
        $this->managerNumber = $managerNumber;
        $this->notifiable = $notifiable;
    }

    /**
     * Convert Laravel URL to IP-based URL
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
                   ->subject($this->isFinal() 
                       ? 'Final Approval Required for Request' 
                       : 'Request Awaiting Your Final Approval')
                   ->markdown('emails.final_approval_request')
                   ->with([
                       'finalRequest' => $this->finalRequest,
                       'url' => $this->url,
                       'managerNumber' => $this->managerNumber,
                       'notifiable' => $this->notifiable,
                       'isFinal' => $this->isFinal()
                   ]);
    }

    /**
     * Determine if this is the final approval stage.
     */
    protected function isFinal(): bool
    {
        return str_contains(strtolower($this->managerNumber), 'final');
    }
}