@component('mail::message')
# Dear {{ $user->name }}
#### Your OTP code is:

# {{ $otp->code }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
