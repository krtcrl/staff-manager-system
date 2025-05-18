<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request as RequestFacade;

class ManagerAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $notifiable;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @param string $password
     * @param mixed $notifiable
     */
    public function __construct($password, $notifiable)
    {
        $this->password = $password;
        $this->notifiable = $notifiable;
        $this->loginUrl = $this->convertToIpUrl(route('login'));
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
                   ->subject('Your Manager Account Has Been Created')
                   ->markdown('emails.manager_account_created')
                   ->with([
                       'password' => $this->password,
                       'notifiable' => $this->notifiable,
                       'loginUrl' => $this->loginUrl
                   ]);
    }
}