@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="{{ url('dev-img/NT_Logo.png') }}" alt="NT Logo" style="height: 50px;">
@endcomponent
@endslot

@php
    $submitter = $staff?->name ?? 'System';
@endphp

# New Request Notification

Hello {{ $notifiable->name }},

A new request requires your immediate attention:

@component('mail::panel')
**Part Number:** {{ $request->part_number }}  
**Part Name:** {{ $request->part_name }}  
**Submitted By:** {{ $submitter }}
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
Review & Approve
@endcomponent

This is an automated notification — please do not reply.

Regards,  
**ST Approval System**

@slot('footer')
<div style="text-align: center; padding: 20px; font-size: 12px; color: #888;">
    © {{ date('Y') }} Standard Time Approval System. All rights reserved.

    <div style="margin-top: 10px;">
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
        <span style="color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none; margin: 0 10px;">Contact Support</a>
    </div>

    <div style="color: #aaa; margin-top: 10px;">
        This email was sent to {{ $notifiable->email }} as part of your responsibilities in the Standard Time Approval System.
    </div>
</div>
@endslot
@endcomponent
