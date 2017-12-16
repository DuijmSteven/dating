@extends('emails.layouts.default.layout')

@section('content')

    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi {{ $user->username }},</p>

    <p>Welcome to {{ config('app.name') }}!
    </p>

    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Best regards, <br>
        {{ config('app.name') }}</p>

@endsection