@extends('emails.system.layout')

@section('content')
    <h1>Your trial has ended</h1>

    <p>Hi {{ $user->first_name }},</p>

    <p>
        Your 14-day free trial has ended and your account is now restricted.
        You will not be able to create invoices, quotes, or access new features until you upgrade.
    </p>

    <p>
        Your data is safe — everything is still here waiting for you.
        Upgrade to a paid plan to restore full access instantly.
    </p>

    <a href="{{ url('/settings/billing') }}" class="btn">Choose a Plan</a>

    <hr class="divider" />

    <p>Need help deciding which plan is right for you? Reply to this email and we will help.</p>

    <p>— The Bill Base Team</p>
@endsection