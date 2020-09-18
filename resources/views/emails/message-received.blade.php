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

@section('unsubscribe')
    <p style="line-height: 15px; text-align: center; font-size: 12px; color: #aaa; font-family: sans-serif;">{{ @trans('unsubscribe.link_text') }}
        <a style="color: #aaa; text-decoration: underline;" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('unsubscribe', ['user' => $messageRecipient->id]); @endphp">
            {{ @trans('unsubscribe.link_click') }}
        </a>
    </p>
@endsection

@section('content')

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Nieuw bericht ontvangen!
    </h1>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">Beste {{ $messageRecipient->username }},</p>



    <p style="margin-bottom: 0">
        Je hebt {{ $hasMessage ? ' een bericht ' : '' }} {{  $hasBoth ? ' met ' : '' }} {{ $hasAttachment ? ' een foto ' : '' }} ontvangen van <b> {{ $messageSender->username }}</b> op <b>Altijdsex.nl</b>. Klik op onderstaande link om het bericht direct te bekijken!
    </p>

    @if($hasMessage)
        <h3 style="margin-bottom: 3px">Het bericht</h3>
        <p style="border: 1px solid #ecebeb;
            padding: 10px;
            border-radius: 7px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            margin-top: 3px;"
        >
            {{ substr($messageBody, 0, 50) }} {{ strlen($messageBody) > 50 ? '...' : '' }}
        </p>
    @endif

    <div style="text-align: center">
        <div class="Tile UserSummary" data-user-id="{!! $messageSender->getId() !!}">
            <div class="Tile__body UserSummary__body">
                <div class="UserSummary__user-image">
                    <a href="{{ route('users.show', ['username' => $messageSender->getUsername()])  }}">
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
                        <a href="{{ route('users.show', ['username' => $messageSender->getUsername()])  }}"
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

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: #ce5338; color: #fff; border: 1px solid #ce5338; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="{{ route('landing-page.show-login') }}">Naar Altijdsex.nl</a>
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
