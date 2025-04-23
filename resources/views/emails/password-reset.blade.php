@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NTLogo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent

@endslot

# Password Reset Request

Hello {{ $userType === 'manager' ? 'Manager' : 'Staff Member' }},

We received a request to reset your password for the **Standard Time Approval System**.

@component('mail::panel')
**Account:** {{ $notifiable->email }}  
**Request Time:** {{ now()->format('F j, Y \a\t g:i A') }}
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Reset Your Password
@endcomponent

This link will expire in **60 minutes** for security reasons. If you didn't request this password reset, please ignore this email or contact support if you have concerns.

@component('mail::subcopy')
If you're having trouble clicking the button, copy and paste this URL into your browser:  
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
            This email was sent to {{ $notifiable->email }} because you requested a password reset.
        </div>
    </div>
@endslot
@endcomponent