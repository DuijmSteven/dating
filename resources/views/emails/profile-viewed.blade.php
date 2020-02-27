@extends('emails.layouts.default.layout')

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        {{ $messageSender->username }} heeft jouw profiel bekeken!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $messageRecipient->username }},</p>

    <p style="margin-bottom: 0">
        {{ $messageSender->username }} heeft zojuist jouw profiel bezocht.
    </p>

    <div style="text-align: center">
        @include('frontend.components.user-summary', ['email' => true, 'carbonNow' => $carbonNow])
    </div>

    <p style="margin-bottom: 0">
        Bekijk zijn/haar volledige profiel door op onderstaande button te klikken. Je kunt dan ook direct een bericht versturen als je in contact wilt komen met deze persoon!
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ef4f2b; color: #fff; border: 1px solid #ef4f2b; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="{{ route('users.show', ['username' => $messageSender->username]) }}">{{ $messageSender->username }}</a>
        </div>
    </div>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

@endsection