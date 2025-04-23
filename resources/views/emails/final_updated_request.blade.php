@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NTLogo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent
@endslot

# Final Request Updated

Hello Manager {{ $managerNumber }},

@component('mail::panel')
The final request with code **{{ $finalRequest->unique_code }}** that you previously rejected has been updated by the staff.  
Please review the updated final request at your earliest convenience.
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'primary'])
View Updated Final Request
@endcomponent

@component('mail::subcopy')
If the button doesn't work, copy and paste this URL in your browser:  
[{{ $url }}]({{ $url }})
@endcomponent

@slot('footer')
<div style="text-align: center; padding: 20px; font-size: 12px; color: #888;">
    © {{ date('Y') }} Standard Time Approval System. All rights reserved.

    <div style="margin-top: 10px;">
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
        <span style="color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Contact Support</a>
    </div>

    <div style="color: #aaa; margin-top: 10px;">
        This email was sent to {{ $notifiable->email }} because you are listed as the approver for this request.
    </div>
</div>
@endslot
@endcomponent
