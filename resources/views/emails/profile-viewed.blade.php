@extends('emails.layouts.default.layout')

@section('unsubscribe')
    <p style="line-height: 15px; text-align: center; font-size: 12px; color: #aaa; font-family: sans-serif;">{{ @trans('unsubscribe.link_text') }}
        <a style="color: #aaa; text-decoration: underline;" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $messageRecipient->id]); @endphp">
            {{ @trans('unsubscribe.link_click') }}
        </a>
    </p>
@endsection

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        {{ $messageSender->username }} heeft jouw profiel bekeken!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $messageRecipient->username }},</p>

    <p style="margin-bottom: 0">
        {{ $messageSender->username }} heeft zojuist jouw profiel bezocht.
    </p>

    <div style="text-align: center">
        <div class="Tile UserSummary" data-user-id="{!! $messageSender->getId() !!}">
            <div class="Tile__body UserSummary__body">
                <div class="UserSummary__user-image">

                    <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $messageRecipient->getId(), 'routeName' => 'users.show', 'routeParamName' => 'username', 'routeParam' => $messageSender->username]); @endphp">
                        <img
                            class="UserSummary__profileImage JS--galleryImage"
                            src="{{ \StorageHelper::profileImageUrl($messageSender) }}"
                            alt="user image"
                        >
                    </a>
                </div>
            </div>
            <div class="Tile__footer UserSummary__footer">
                <div class="UserSummary__footer__upperPart">
                    <div class="UserSummary__userInfo">
                        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $messageRecipient->getId(), 'routeName' => 'users.show', 'routeParamName' => 'username', 'routeParam' => $messageSender->username]); @endphp"
                           class="UserSummary__userInfo__primary">
                            {{ $messageSender->username }}
                        </a>
                        <div class="UserSummary__userInfo__additional">
                            @if(isset($messageSender->meta->city) && $messageSender->meta->dob)
                                {{ $messageSender->meta->city . ', ' }}
                            @elseif ($messageSender->meta->city)
                                {{ $messageSender->meta->city }}
                            @endif

                            {!! $messageSender->meta->dob ? $messageSender->meta->dob->diffInYears($carbonNow) . ' Jaar' : '' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p style="margin-bottom: 0">
        Bekijk zijn/haar volledige profiel door op onderstaande button te klikken. Je kunt dan ook direct een bericht versturen als je in contact wilt komen met deze persoon!
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $messageRecipient->getId(), 'routeName' => 'users.show', 'routeParamName' => 'username', 'routeParam' => $messageSender->username]); @endphp">Bekijk het profiel</a>
        </div>
    </div>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Altijdsex.nl
    </p>

    <p style="text-align: center; font-size: 16px; margin-top: 30px;">{{ @trans('unsubscribe.link_text') }}
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $messageRecipient->id]); @endphp">
            {{ @trans('unsubscribe.link_click') }}
        </a>
    </p>

@endsection
