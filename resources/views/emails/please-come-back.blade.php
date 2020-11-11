@extends('emails.layouts.default.layout')

@section('unsubscribe')
    <p style="line-height: 15px; text-align: center; font-size: 12px; color: #aaa; font-family: sans-serif;">{{ @trans(config('app.directory_name') . '/unsubscribe.link_text') }}
        <a style="color: #aaa; text-decoration: underline;" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $user->id]); @endphp">
            {{ @trans(config('app.directory_name') . '/unsubscribe.link_click') }}
        </a>
    </p>
@endsection

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Tijdelijk 15% korting voor je, {{ ucfirst($user->username) }}!
    </h1>

    <p style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

    <p style="margin-bottom: 0">
        Enige tijd geleden was je voor het laatst actief op
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'credits.show', null, null]); @endphp">Altijdsex.nl</a>
        en een aantal gewillige leden hebben aangegeven je te missen! Zoek je nog anonieme, vrijblijvende sensuele contacten? Kom dan gerust terug naar
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'credits.show', null, null]); @endphp">Altijdsex.nl</a>
        en profiteer van <b>15% korting</b>.
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'credits.show', null, null]); @endphp">Ga direct naar credits pagina!</a>
        </div>
    </div>

    <p style="margin-bottom: 0">
        Elke dag weer zijn er duizenden vrouwen die zich met dezelfde intenties inschrijven op
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'credits.show', null, null]); @endphp">Altijdsex.nl</a>.
        Daar zal vast en zeker ook wel een gelijkgestemde voor jou tussen zitten, toch? Speciaal om jouw kansen te vergroten bieden we je momenteel <b>15% korting</b> op je volgende aankoop.
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'credits.show', null, null]); @endphp">Profiteer van deze korting!</a>
        </div>
    </div>

    <p>Geniet van deze aanbieding en een heel spannende tijd toegewenst!</p>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

    <p style="text-align: center; font-size: 16px; margin-top: 30px;">{{ @trans(config('app.directory_name') . '/unsubscribe.link_text') }}
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $user->id]); @endphp">
            {{ @trans(config('app.directory_name') . '/unsubscribe.link_click') }}
        </a>
    </p>

@endsection
