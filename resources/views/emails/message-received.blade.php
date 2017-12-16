@extends('emails.layouts.default.layout')

@section('content')

    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi {{ $sender->username }},</p>

    <p>You have received a new message from {{ $recipient->username }}.
    </p>

    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Best regards, <br>
        {{ config('app.name') }}</p>

@endsection