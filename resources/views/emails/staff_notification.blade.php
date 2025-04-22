@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NT_Logo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent
@endslot

# {{ $subject }}

{{ $message }}

@component('mail::button', ['url' => $url])
{{ $action }}
@endcomponent

Thank you for using our application!

@slot('footer')
<div style="text-align: center; padding: 20px; font-size: 12px; color: #888;">
    © {{ date('Y') }} Standard Time Approval System. All rights reserved.

    <div style="margin-top: 10px;">
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
        <span style="color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Contact Support</a>
    </div>

    <div style="color: #aaa; margin-top: 10px;">
        This email was sent to {{ $notifiable->email }} because you have an active request in our system.
    </div>
</div>
@endslot
@endcomponent
