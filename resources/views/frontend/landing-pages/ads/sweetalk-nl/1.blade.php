<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Een Date Zoeken en Geil Chatten | {{ ucfirst(config('app.name')) }}</title>
    <meta name="description" content="Anoniem geil chatten met single vrouwen met interesse in mannen dating, vind een vrouw voor sex chat, sekscontact of zoek date met een seksbuddy.">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    @if(App::environment('production'))
    <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-MJG2S4N');</script>
        <!-- End Google Tag Manager -->
    @endif

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    @include('frontend.landing-pages.common-google-captcha-part')

    <style>
        body {
            /*font-size: 1.9rem;*/
            font-weight: 300;

            font-family: 'Fira Sans', sans-serif;
        }

        .bg-img {
            /* Control the height of the image */
            min-height: 680px;

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .bg-img form {
            position: absolute;
            right: 0;
            max-width: 500px;
            padding: 16px;
            background-color: white;
            font-size: 14px;
        }

        .imgHeader {
            background: url('/lps/t1/assets/img/lp-3.jpg') center/cover no-repeat;
            min-height: 120px;
        }

        .logo img {
            width: 100px;
        }

        @media (min-width: 768px) {
            .bg-img {
                background-image: url("/lps/t1/assets/img/lp-3.jpg");
            }

            .bg-img form {
                margin: 20px;
            }

            .logo img {
                width: 180px;
            }
        }

    </style>
</head>
<body>
    @if(App::environment('production'))
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJG2S4N"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
    <div class="main">
        <div class="container-fluid d-sm-block d-md-none">
            <div class="row mt-1 mb-1 text-center">
                <div class="col my-auto">
                    <div class="logo">
                        <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/main_logo.png') !!}">
                    </div>
                </div>
            </div>
        </div>
        <div class="row imgHeader d-sm-block d-md-none">
            <div class="col-md-12 my-auto text-center text-white" style="background-color: black; opacity: 0.6;">
                <h6>LET OP: Je kunt hier bekenden tegenkomen!</h6>
                <span>Tijdelijk gratis inschrijving, verloopt over: </span>
                <span class="time" style="color: #f44336; font-weight: bold">05:00</span>
            </div>
        </div>
        <div class="bg-img">
            <div class="container">
                <div style="position: relative">
                    <form class="pt-0" id="JS--registrationForm" method="POST" action="{{ route('register.post') }}" autocomplete="off">
                        {{ csrf_field() }}

                        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                        <input type="hidden" name="action" value="validate_captcha">

                        <input id="userFingerprintInput" type="hidden" name="user_fingerprint" value="">

                        @if(isset($mediaId))
                            <input type="hidden" name="mediaId" value="{{ $mediaId }}">
                        @endif

                        @if(isset($clickId))
                            <input type="hidden" name="clickId" value="{{ $clickId }}">
                            <input type="hidden" name="affiliate" value="{{ $affiliate }}">
                            <input type="hidden" name="country" value="{{ $country }}">
                        @elseif(isset($affiliate) && $affiliate === \App\UserAffiliateTracking::AFFILIATE_DATECENTRALE)
                            <input type="hidden" name="affiliate" value="{{ \App\UserAffiliateTracking::AFFILIATE_DATECENTRALE }}">
                        @endif

                        <div class="text-center my-auto d-none d-md-block pt-2 pb-2">
                            <div class="logo">
                                <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/main_logo.png') !!}">
                            </div>
                        </div>

                        <div class="text-center d-none d-md-block p-2" style="background-color: bisque;">
                            <h5>LET OP: Je kunt hier bekenden tegenkomen!</h5>
                            <h6 class="mb-0">Tijdelijk gratis inschrijving, verloopt over:
                                <span class="time" style="color: #f44336; font-weight: bold">05:00</span>
                            </h6>
                        </div>

                        @if(session()->has('recaptchaFailed') && session()->get('recaptchaFailed'))
                            <div class="captchaFailed">
                                {{ @trans('lp1.captcha_failed_message') }}
                            </div>
                        @endif

                        @if ($errors->first('fingerprintExists'))
                            <div class="captchaFailed">
                                Het ziet uit als je al een account heb! Als dat niet waar is neem contact op met de helpdesk.
                            </div>
                        @endif

                        <div class="form-row mt-2">
                            <div class="form-group col-md-12">
                                <label for="lookingFor">{{ @trans('lp1.form.i_am') }}:</label>
                                <select class="form-control" id="lookingFor" name="lookingFor" style="font-size: 14px">
                                    <option value="male-female" {{ old('lookingFor') === 'male-female' ? 'selected' : '' }}>{{ @trans('lp1.form.man_looking_for_woman') }}</option>
                                    <option value="female-male" {{ old('lookingFor') === 'female-male' ? 'selected' : '' }}>{{ @trans('lp1.form.woman_looking_for_man') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="formLabelSmall" for="email">E-mailadres (niet zichtbaar)</label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                >
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="name">{{ @trans('lp1.form.username') }}</label>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       value="{{ old('username') }}"
                                       required
                                >
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">{{ @trans('lp1.form.password') }}</label>
                                <input type="text" class="form-control" id="password" name="password"
                                       required
                                >
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-12 submit">
                                <button type="submit"
                                        class="JS--register-button btn btn-success btn-lg btn-block">{{ @trans('lp1.form.register_now') }}</button>
                            </div>

                            <div class="col-xs-12">
                                <p class="mt-3" style="font-size: 11px; text-align: justify">
                                    {!! @trans(
                                            'lp1.form.register_info',
                                            [
                                                'privacyRoute' => route('privacy.show'),
                                                'tacRoute' => route('tac.show'),
                                            ]
                                        )
                                    !!}
                                </p>
                            </div>
                            <div class="col-xs-12 mx-auto">
                                <span>Heb je al een account?</span>
                                <a href="{{ route('landing-page.show-login') }}" class="btn btn-secondary btn-sm">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h3>Contact vrouwen</h3>
                        <p style="font-size: 18px; font-weight: 300; text-align: justify;">Online chatten begint op een datingsite, zo ook het contact met vrouwen. Sex chat en online chatten, in het bijzonder anoniem geil chatten, zijn bijzonder goede manieren om je toekomstige bedpartner beter te leren kennen. Het fijne is dat je nog weinig van jezelf bloot hoeft te geven, maar wel kunt uitvinden of iemand bij je past. Sekscontact gaat vaak verder dan gewoon kennismaken, aangezien je toch enige verbinding wilt hebben met degene waar je na de sex chat mee het bed in duikt.</p>
                        <h3>Vreemdgaan</h3>
                        <p style="font-size: 18px; font-weight: 300; text-align: justify;">Vind een vrouw om vreemd mee te gaan, klinkt makkelijker dan het is. Hoe doe je dat eigenlijk en hoeveel vrouwen zoeken mannen voor dezelfde redenen? Vrouw zoekt man komt net zo vaak voor als man zoekt vrouw óók als het om vreemdgaan gaat. Belangrijk is dat dit geheel discreet gebeurd, vandaar dat de aanmelding geheel anoniem verloopt en er geen persoonlijke gegevens worden vastgelegd. Dit zodat jij in alle rust kan chatten met Nederlanders!</p>
                        <h3>Vrouw vinden</h3>
                        <p style="font-size: 18px; font-weight: 300; text-align: justify; margin-bottom: 30px">Na het gratis registreren kun je eenvoudig op zoek gaan naar sekscontact met vrouwen online. Of je nu een oudere vrouw zoekt, singles of gebonden mensen. Na inschrijving kun je met je gratis credit direct sekscontact leggen en online chatten op deze datingsite voor sekscontacten!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center" style="margin-bottom: 30px">
                    <a style="color: #337ab7" href="{{ route('tac.show') }}">{{ @trans('footer.tac') }}</a> - <a  style="color: #337ab7" href="{{ route('contact.get') }}">{{ @trans('footer.contact') }}</a>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script>
    var DP = {
        baseUrl: '{!! url('/') !!}',
        locale: '{{ app()->getLocale() }}',
        recaptchaKey: '{{ config('app.recaptcha_key') }}',
        recaptchaSecret: '{{ config('app.recaptcha_secret') }}',
    };

    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            for (i = 0; i < display.length; i++) {
                display[i].textContent = minutes + ":" + seconds;
            }

            if (--timer < 0) {
                timer = 0;
            }
        }, 1000);
    }

    window.onload = function () {
        var fiveMinutes = 60 * 5,
            display = document.querySelectorAll('.time');
        startTimer(fiveMinutes, display);
    };
</script>

</html>
