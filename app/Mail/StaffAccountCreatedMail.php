<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request as RequestFacade;

class StaffAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $loginUrl;
    public $notifiable;

    public function __construct($password, $loginUrl, $notifiable)
    {
        $this->password = $password;
        $this->loginUrl = $this->convertToIpUrl($loginUrl);
        $this->notifiable = $notifiable;
    }

    protected function convertToIpUrl($originalUrl)
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

    public function build()
    {
        return $this->markdown('emails.staff_account_created')
                   ->subject('Your Staff Account Has Been Created')
                   ->with([
                       'password' => $this->password,
                       'loginUrl' => $this->loginUrl,
                       'user' => $this->notifiable
                   ]);
    }
}