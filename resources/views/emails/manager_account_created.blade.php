@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NT_Logo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent
@endslot

# Your Manager Account Has Been Created

Hello {{ $notifiable->name }},

Your manager account has been created by the administrator.

Here are your login details:

- **Manager #**: {{ $notifiable->manager_number }}
- **Email**: {{ $notifiable->email }}
- **Password**: {{ $password }}

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

Please change your password after logging in.

As a manager, you have additional system privileges. Keep your credentials secure.

@slot('footer')
<div style="text-align: center; padding: 20px; font-size: 12px; color: #888;">
    © {{ date('Y') }} Standard Time Approval System. All rights reserved.

    <div style="margin-top: 10px;">
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
        <span style="color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Contact Support</a>
    </div>

    <div style="color: #aaa; margin-top: 10px;">
        This email was sent to {{ $notifiable->email }} because you have been registered as a manager.
    </div>
</div>
@endslot
@endcomponent
