@extends('emails.system.layout')

@section('content')
    <h1>Your trial ends in {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}</h1>

    <p>Hi {{ $user->first_name }},</p>

    <p>
        Your free trial ends on <strong>{{ $trialEndsAt->format('d M Y') }}</strong>.
        After that, your account will be restricted and you will no longer be able to create
        invoices, quotes, or access your customer data.
    </p>

    <p>Upgrade now to keep everything running without interruption.</p>

    <a href="{{ url('/settings/billing') }}" class="btn">Choose a Plan</a>

    <hr class="divider" />

    <p>Questions? Reply to this email and we will help you find the right plan.</p>

    <p>— The Bill Base Team</p>
@endsection