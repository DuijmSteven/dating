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
        Account gedeactiveerd
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

    <p>
        Je account is bij deze per direct gedeactiveerd.
    </p>

    <p>
        We vinden het jammer om jou te zien gaan! Wanneer u besluit uw profiel opnieuw te gebruiken, wordt dit automatisch geactiveerd zodra u zich aanmeldt!</p>

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
