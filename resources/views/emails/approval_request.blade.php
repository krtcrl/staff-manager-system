@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NT_Logo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent
@endslot

# {{ $isFinal ? 'Final Approval Required' : 'Approval Request' }}

Hello {{ $notifiable->name }},

@component('mail::panel')
**Part Number:** {{ $request->part_number }}  
**Part Name:** {{ $request->part_name }}  
@if (!$isFinal)
**Manager:** {{ $managerNumber }}
@endif
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'primary'])
{{ $isFinal ? 'Review Final Approval Request' : 'Review Request' }}
@endcomponent

@if ($isFinal)
This request has passed all prior approvals and now awaits **final sign-off**.
@else
You are requested to **approve or reject** this request as part of the workflow.
@endif

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
        This email was sent to {{ $notifiable->email }} because you are listed as an approver.
    </div>
</div>
@endslot
@endcomponent
