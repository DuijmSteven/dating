@extends('emails.layouts.default.layout')

@section('unsubscribe')
    <p style="line-height: 15px; text-align: center; font-size: 12px; color: #aaa; font-family: sans-serif;">{{ trans(config('app.directory_name') . '/unsubscribe.link_text') }}
        <a style="color: #aaa; text-decoration: underline;" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $user->id]); @endphp">
            {{ trans(config('app.directory_name') . '/unsubscribe.link_click') }}
        </a>
    </p>
@endsection

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Verbeter jouw profiel en vergroot jouw kans op succes!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Je staat nu al even ingeschreven op <a href="{{ route('home') }}">{{ ucfirst(config('app.name')) }}</a>. Jouw profielinformatie is echter nog zeer beperkt, waardoor je mogelijk contact of zelfs een hete sex date misloopt. Door onderstaande tip(s) in acht te nemen verbeter je direct je profiel.

    </p>

    <div class="Tile LowProfileCompletion">
        <div class="Tile__body">
            @if(!$user->profileImage)
                <div style="margin-bottom: 10px">
                    <span style="font-size: 19px; color: #333; font-style: normal; margin-right: 5px"><b>- Profielfoto instellen</b></span>
                    <span style="font-family: sans-serif; font-weight: normal; margin: 0;">Een profielfoto is het belangrijkste onderdeel van jouw profiel</span>
                </div>
            @endif

            <div style="margin-bottom: 20px">
                <span style="font-size: 19px; color: #333; font-style: normal; margin-right: 5px"><b>- Profielvelden invullen</b></span>

                <?php
                    if ($user->profileRatioFilled < 0.3) {
                        $color = 'red';
                    } elseif ($user->profileRatioFilled < 0.7) {
                        $color = 'orange';
                    } else {
                        $color = 'green';
                    }
                ?>

                <span style="font-family: sans-serif; font-weight: normal; margin: 0; margin-bottom: 15px;">Jouw profiel is <span style="color: {{ $color }}"><b>{{ $user->profileRatioFilled*100 }}%</b></span> compleet</span>
            </div>

            <p style="font-family: sans-serif; font-weight: normal; margin: 0; margin-bottom: 15px;">
                Leden die een <a href="{{ route('users.edit-profile.get', ['username' => $user->username]) }}">profielfoto uploaden</a>
                en hun <a href="{{ route('users.edit-profile.get', ['username' => $user->username]) }}">profielinformatie invullen</a>,
                hebben <b>70% meer kans op contact</b> en een successvolle sexdate!
            </p>


        </div>

    </div>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->id, 'routeName' => 'home', null, null]); @endphp">Ga direct naar Altijdsex.nl</a>
        </div>
    </div>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team {{ ucfirst(config('app.name')) }}
    </p>

    <p style="text-align: center; font-size: 13px!important; margin-top: 30px;">{{ trans(config('app.directory_name') . '/unsubscribe.link_text') }}
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $user->id]); @endphp">
            {{ trans(config('app.directory_name') . '/unsubscribe.link_click') }}
        </a>
    </p>

@endsection
