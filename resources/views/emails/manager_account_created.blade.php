@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NTLogo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent
@endslot

# Your Manager Account Has Been Created

Hello {{ $notifiable->name }},

Your manager account has been created by the administrator. 

Here are your login details:

- **Manager #**: {{ $notifiable->manager_number }}
- **Email**: {{ $notifiable->email }}
- **Role**: {{ ucfirst($notifiable->role) }}
- **Temporary Password**: {{ $password }}

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

## Important Security Instructions:

1. Please change your password immediately after logging in.
2. Never share your login credentials with anyone.
3. As a {{ $notifiable->role }}, you have elevated system privileges - use them responsibly.

@if($notifiable->role === 'admin')
<div style="background-color: #f8f5ff; border-left: 4px solid #8b5cf6; padding: 12px; margin: 16px 0; border-radius: 4px;">
    <strong>Admin Notice:</strong> Your role grants you full system access. Please review all security protocols.
</div>
@elseif($notifiable->role === 'supervisor')
<div style="background-color: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 12px; margin: 16px 0; border-radius: 4px;">
    <strong>Supervisor Notice:</strong> You can approve requests and manage team schedules.
</div>
@else
<div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 12px; margin: 16px 0; border-radius: 4px;">
    <strong>Manager Notice:</strong> You can manage staff and oversee daily operations.
</div>
@endif

@slot('footer')
<div style="text-align: center; padding: 20px; font-size: 12px; color: #888;">
    © {{ date('Y') }} Standard Time Approval System. All rights reserved.

    <div style="margin-top: 10px;">
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
        <span style="color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Contact Support</a>
    </div>

    <div style="color: #aaa; margin-top: 10px;">
        This email was sent to {{ $notifiable->email }} because you have been registered as a {{ $notifiable->role }}.
        @if($notifiable->role === 'admin')
        <br>You are receiving this sensitive information because of your administrative privileges.
        @endif
    </div>
</div>
@endslot
@endcomponent