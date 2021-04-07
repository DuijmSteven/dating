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

    <link rel="stylesheet" href="{{ mix('css/liefdesdate-nl/adsLps.css') }}">

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

    @include('frontend.layouts.default.partials.favicons')
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
        @if($lpType === 'register')

            <div class="col-md-12 text-center text-white warning-box warning-bg">
                <h6>{{ $users ? count($users) + 6786 : 6786 }} geregistreerde gebruikers!</h6>
                <span>GRATIS Inschrijven!</span>
                <?php /*<span>Tijdelijk gratis inschrijving, verloopt over: <span class="time" style="color: #f44336; font-weight: bold">05:00</span></span>*/ ?>
            </div>
        @endif
    </div>
    <div class="bg-img {{ $lpType === 'login' ? 'login' : 'register' }} {{ (!config('app.show_images') && config('app.env') === 'local') ? 'imageDontShowLocal' : '' }}">
        <div class="container">
            <div style="position: relative">

                @if($lpType === 'login')
                    <form class="pt-0 loginForm" method="POST" action="{{ route('login.post') }}" autocomplete="off">

                        {{ csrf_field() }}

                        <div class="text-center my-auto d-none d-md-block pt-2 pb-2">
                            <div class="logo">
                                <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/main_logo.png') !!}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="enhancedFormGroup JS--enhancedFormGroup form-group col-md-12 {{ $errors->has('identity') || $errors->has('username') || $errors->has('email') ? ' has-error' : '' }}">
                                <label for="login-identity">{{ trans(config('app.directory_name') . '/lp1.form.identity') }}</label>
                                <input type="text" class="form-control enlarged" id="login-identity" name="identity"
                                       value="{{ old('identity') }}"
                                       required autofocus
                                >
                                @if($errors->has('identity'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('identity') }}</strong>
                                    </span>
                                @endif

                                @if($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif

                                @if($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="enhancedFormGroup JS--enhancedFormGroup form-group col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="login-password">{{ trans(config('app.directory_name') . '/lp1.form.password') }}</label>
                                <input type="password" class="form-control enlarged" id="login-password" name="password"
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
                                        class="btn btn-login btn-lg btn-block">{{ trans(config('app.directory_name') . '/lp1.form.login') }}</button>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top: 20px; margin-bottom: 20px">
                            <div class="col-xs-12 mx-auto">
                                <span>{{ trans(config('app.directory_name') . '/lp1.form.not_have_an_account') }}</span>
                                <a href="{{ route('ads-lp.show', ['id' => $id, 'lpType' => 'register']) }}" class="btn btn-register btn-sm">Register</a>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-xs-12 mx-auto">
                                <span>{{ trans(config('app.directory_name') . '/lp1.forgot_password') }}</span>
                                <a href="{{ route('password.reset.get') }}" class="btn btn-secondary btn-sm">{{ trans(config('app.directory_name') . '/lp1.reset_password') }}</a>
                            </div>
                        </div>
                    </form>
                @else
                    <form class="pt-0" id="JS--registrationForm" method="POST" action="{{ route('register.post') }}" autocomplete="off">
                        {{ csrf_field() }}

                        @include('frontend.landing-pages.common-registration-form-part')

                        <div class="text-center my-auto d-none d-md-block pt-2 pb-2">
                            <div class="logo">
                                <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/main_logo.png') !!}">
                            </div>
                        </div>

                        <div class="text-center text-white d-none d-md-block p-2 warning-bg">
                            <h5 class="mb-0">{{ $users ? count($users) + 6786 : 6786 }} geregistreerde gebruikers!</h5>
                            <h6 class="mb-0 mt-2">GRATIS Inschrijven!</h6>
                        </div>

                        @if(session()->has('recaptchaFailed') && session()->get('recaptchaFailed'))
                            <div class="captchaFailed">
                                {{ trans(config('app.directory_name') . '/lp1.captcha_failed_message') }}
                            </div>
                        @endif

                        <div class="form-row mt-2">
                            <div class="form-group col-md-12">
                                <label for="lookingFor">{{ trans(config('app.directory_name') . '/lp1.form.i_am') }}:</label>
                                <select class="form-control" id="lookingFor" name="lookingFor" style="font-size: 14px">
                                    <option value="male-female" {{ old('lookingFor') === 'male-female' ? 'selected' : '' }}>{{ trans(config('app.directory_name') . '/lp1.form.man_looking_for_woman') }}</option>
                                    <option value="female-male" {{ old('lookingFor') === 'female-male' ? 'selected' : '' }}>{{ trans(config('app.directory_name') . '/lp1.form.woman_looking_for_man') }}</option>
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
                                <label for="name">{{ trans(config('app.directory_name') . '/lp1.form.username') }}</label>
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
                                <label for="password">{{ trans(config('app.directory_name') . '/lp1.form.password') }}</label>
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
                                        class="JS--register-button btn btn-register btn-lg btn-block">{{ trans(config('app.directory_name') . '/lp1.form.register_now') }}</button>
                            </div>

                            <div class="col-xs-12">
                                <p class="mt-3" style="font-size: 11px; text-align: justify">
                                    {!! trans(config('app.directory_name') . '/lp1.form.register_info',
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
                                <a href="{{ route('ads-lp.show', ['id' => $id, 'lpType' => 'login']) }}" class="btn btn-login btn-sm">Login</a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

{{--    <div class="container welcome-container text-center JS--welcome-container" style="margin-bottom: 8rem;">--}}

{{--        <div class="row icons" style="margin-top: 75px">--}}
{{--            <div class="col-xs-12 col-md-4 col-sm-12">--}}
{{--                <i class="material-icons">--}}
{{--                    how_to_reg--}}
{{--                </i>--}}
{{--                <h4>{{ trans(config('app.directory_name') . '/lp1.usps.free_access.title') }}</h4>--}}
{{--                <p style="text-align: left">{{ trans(config('app.directory_name') . '/lp1.usps.free_access.text') }}</p>--}}
{{--            </div>--}}
{{--            <div class="col-xs-12 col-md-4 col-sm-12">--}}
{{--                <i class="material-icons">--}}
{{--                    chat--}}
{{--                </i>                <h4>{{ trans(config('app.directory_name') . '/lp1.usps.live_chat.title') }}</h4>--}}
{{--                <p style="text-align: left">{{ trans(config('app.directory_name') . '/lp1.usps.live_chat.text') }}</p>--}}
{{--            </div>--}}
{{--            <div class="col-xs-12 col-md-4 col-sm-12">--}}
{{--                <i class="material-icons">--}}
{{--                    vpn_lock--}}
{{--                </i>--}}
{{--                <h4>{{ trans(config('app.directory_name') . '/lp1.usps.privacy_anonymity.title') }}</h4>--}}
{{--                <p style="text-align: left">{{ trans(config('app.directory_name') . '/lp1.usps.privacy_anonymity.text') }}</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-md-4 text-center">
                <i class="material-icons " style="color: #ebbcbc; font-size: 7em">
                    textsms
                </i>
                <h3> >75.000 berichten per maand</h3>
            </div>
            <div class="col-md-4 text-center">
                <i class="material-icons" style="color: #ebbcbc; font-size: 7em">
                    group
                </i>
                <h3>58% / 42% vrouw / man balans</h3>
            </div>
            <div class="col-md-4 text-center">
                <i class="material-icons" style="color: #ebbcbc; font-size: 7em">
                    insights
                </i>
                <h3>1 op de 3 vindt een relatie</h3>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-sm-12">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4 my-md-auto text-center">
                            <i class="material-icons" style="color: #ebbcbc; font-size: 5em">
                                lock
                            </i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h3 class="card-title">
                                    Veilig Daten
                                </h3>
                                <p class="card-text">
                                    Veilig daten is in deze tijd aan de orde van de dag en hier worden gedegen maatregelen voor getroffen. Zo is de verbinding met {{ ucfirst(config('app.pure_name')) }} te allen tijde versleuteld, waardoor je gegevens niet in handen van vreemden terecht kunnen komen. Ook kun je geheel anoniem daten op deze datingsite, waardoor jezelf besluit of en wanneer je je identiteit bekend maakt tegenover een toekomstige date.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php /*
        <div class="row mt-5">
            @foreach ($users as $user)
                <div class="text-center col-xs-6 col-lg-2 col-md-4 col-sm-6 mb-4 {{ $loop->index > 5 ? 'hidden-xs' : '' }}">
                    <a href="#" class="scrollToRegistration">
                        <img
                            src="{{ $user->profileImageUrlThumb }}"
                            class="roundImage"
                            alt="profile-image"
                        >
                        <h5 class="small recentUsername">{{ $user->username }}</h5>
                        <p class="offset-0 user-info">
                            <small class="text-muted">{{ $user->meta->city }}</small>
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
        */ ?>
{{--        <div class="row">--}}
{{--            <div class="col-sm-12 text-center" style="margin-bottom: 30px">--}}
{{--                <a style="color: #337ab7" href="{{ route('tac.show') }}">{{ trans(config('app.directory_name') . '/footer.tac') }}</a> - <a  style="color: #337ab7" href="{{ route('contact.get') }}">{{ trans(config('app.directory_name') . '/footer.contact') }}</a>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</div>

@include('frontend.layouts.default.partials.sites.liefdesdate-nl.footer')

</body>

<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script>
    (function($){
        var DP = {
            baseUrl: '{!! url('/') !!}',
            locale: '{{ app()->getLocale() }}',
            recaptchaKey: '{{ config('app.recaptcha_key') }}',
            recaptchaSecret: '{{ config('app.recaptcha_secret') }}',
        };

        $('.JS--register-button').click(function (event) {
            grecaptcha.execute(DP.recaptchaKey, {action: 'register'}).then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;

                $('#JS--registrationForm').submit();
            });
        });

        $('.scrollToRegistration').click(() => {
            $('html, body').animate({scrollTop:0}, 500, 'swing');
        });

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
    })(jQuery);
</script>

</html>
