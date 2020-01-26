@extends('emails.layouts.default.layout')

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Welkom {{ $user->username }}!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

    <p>
        Bedankt voor je registratie op <b>Altijdsex.nl</b>. Middels de gekozen inloggegevens kun je nu direct inloggen om je profiel compleet te maken.
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ef4f2b; color: #fff; border: 1px solid #ef4f2b; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="{{ route('landing-page.show') }}">Naar Altijdsex.nl</a>
        </div>
    </div>

    <p>
        Heb je nog vragen over je account of over de website? Lees de veelgestelde vragen eens of neem gerust contact met ons op!
    </p>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

@endsection