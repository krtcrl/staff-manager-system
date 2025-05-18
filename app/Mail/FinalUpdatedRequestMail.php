<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request as RequestFacade;

class FinalUpdatedRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $finalRequest;
    public $url;
    public $managerNumber;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $finalRequest
     * @param  string  $url
     * @param  int  $managerNumber
     * @param  mixed  $notifiable
     */
    public function __construct($finalRequest, $url, $managerNumber, $notifiable)
    {
        $this->finalRequest = $finalRequest;
        $this->url = $this->convertToIpUrl($url);
        $this->managerNumber = $managerNumber;
        $this->notifiable = $notifiable;
    }

    /**
     * Convert Laravel URL to IP-based URL
     *
     * @param  string  $originalUrl
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
                   ->subject('Final Request Updated')
                   ->markdown('emails.final_updated_request')
                   ->with([
                       'finalRequest' => $this->finalRequest,
                       'url' => $this->url,
                       'managerNumber' => $this->managerNumber,
                       'notifiable' => $this->notifiable
                   ]);
    }
}