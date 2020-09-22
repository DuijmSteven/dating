@extends('emails.layouts.default.layout')

@section('unsubscribe')
    <p style="line-height: 15px; text-align: center; font-size: 12px; color: #aaa; font-family: sans-serif;">{{ @trans('unsubscribe.link_text') }}
        <a style="color: #aaa; text-decoration: underline;" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $user->id]); @endphp">
            {{ @trans('unsubscribe.link_click') }}
        </a>
    </p>
@endsection

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Creditpack gekocht!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

    <p>
        Bedankt voor je aankoop op <b>Altijdsex.nl</b>, deze is in goede orde verwerkt en de gekochte credits zijn direct toegevoegd aan je account.
    </p>

    <p>
        Hieronder zie je een overzicht van jouw bestelling:
    </p>

    <p>
        <b>Creditpack</b>: {{ $creditPack->name }} <br>
        <b>Aantal credits</b>: {{ $creditPack->credits }} <br>
        <b>Prijs</b>: &euro;{{ $transactionTotal }} <br>
    </p>

    <p>
        Heb je vragen of opmerkingen over je bestelling? Neem gerust contact met ons op!
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'home', null, null]); @endphp">Ga direct naar Altijdsex.nl</a>
        </div>
    </div>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

    <p style="text-align: center; font-size: 16px; margin-top: 30px;">{{ @trans('unsubscribe.link_text') }}
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $user->id]); @endphp">
            {{ @trans('unsubscribe.link_click') }}
        </a>
    </p>

@endsection
