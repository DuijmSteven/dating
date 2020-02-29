@extends('emails.layouts.default.layout')

<style>
    .UserSummary__profileImage {
        width: 60%;
        padding: 50px 0;
    }

    @media screen and (max-width: 767px) {
        .UserSummary__profileImage {
            width: 80%;
            padding: 30px 0;
        }
    }
</style>

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Nieuw bericht ontvangen!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $messageRecipient->username }},</p>

    <p style="margin-bottom: 0">
        Je hebt een bericht ontvangen van <b> {{ $messageSender->username }}</b> op <b>Altijdsex.nl</b>. Klik op onderstaande link om het bericht direct te bekijken!
    </p>

    <div style="text-align: center">
        @include('frontend.components.user-summary', ['email' => true])
    </div>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="{{ route('landing-page.show') }}">Naar Altijdsex.nl</a>
        </div>
    </div>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

@endsection