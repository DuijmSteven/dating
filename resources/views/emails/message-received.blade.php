@extends('emails.layouts.default.layout')

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Nieuw bericht ontvangen!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $messageRecipient->username }},</p>

    <p style="margin-bottom: 0">
        Je hebt een bericht van <b> {{ $messageSender->username }}</b> ontvangen op <b>Altijdsex.nl</b>. Klik op onderstaande link om het bericht direct te bekijken!
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ef4f2b; color: #fff; border: 1px solid #ef4f2b; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="{{ route('landing-page.show') }}">Naar Altijdsex.nl</a>
        </div>
    </div>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

@endsection