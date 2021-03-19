<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Een Date Zoeken en Geil Chatten | Altijdsex.nl</title>
    <meta name="description"
          content="Anoniem geil chatten met single vrouwen met interesse in mannen dating, vind een vrouw voor sex chat, sekscontact of zoek date met een seksbuddy.">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="canonical" href="https://altijdsex.nl/lps/1"/>

    <link rel="stylesheet" href="{{ mix('css/altijdsex-nl/adsLps.css') }}">

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

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"/>

    <!-- CSS Files -->
    <link href="/lps/t1/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/lps/t1/assets/css/material-bootstrap-wizard.css?v=12" rel="stylesheet"/>

    @include('frontend.landing-pages.common-google-captcha-part')

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
<div class="image-container set-full-height" style="background-image: url('/lps/t1/assets/img/hot-woman.jpg')">
    <a href="#">
        <div class="logo-container text-center">
            <div class="logo">
                <img
                    src="{!! asset('img/site_logos/' . config('app.directory_name') . '/Altijdsex_LogoSmall_Pos.svg') !!}"
                    width="200px">
            </div>
        </div>
    </a>

    <!--   Big container   -->
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <!--      Wizard container        -->
                <div class="wizard-container">
                    <div class="card wizard-card" data-color="red" id="wizard">
                        <form id="JS--registrationForm" action="{{ route('register.post') }}" method="POST"
                              autocomplete="off">
                            {{ csrf_field() }}

                            <input type="hidden" name="registration_lp" value="{{ \App\LP::ADS_LP_1 }}">

                            @include('frontend.landing-pages.common-registration-form-part')

                            <div class="wizard-header">
                                <h3 class="wizard-title">
                                    LET OP: Je kunt hier bekenden tegenkomen!
                                </h3>
                                <h5>Tijdelijk gratis inschrijving, verloopt over: <span id="time"
                                                                                        style="color: #f44336; font-weight: bold">05:00</span>
                                </h5>

                                @if(session()->has('recaptchaFailed') && session()->get('recaptchaFailed'))
                                    <div class="captchaFailed">
                                        {{ trans(config('app.directory_name') . '/lp1.captcha_failed_message') }}
                                    </div>
                                @endif

                                @if ($errors->first('fingerprintExists'))
                                    <div class="captchaFailed">
                                        Het ziet uit als je al een account heb! Als dat niet waar is neem contact op met
                                        de helpdesk.
                                    </div>
                                @endif
                            </div>
                            <div class="wizard-navigation">
                                <ul>
                                    <li><a href="#captain" data-toggle="tab">Ik ben Op zoek</a></li>
                                    <li><a href="#details" data-toggle="tab">Registreren</a></li>
                                </ul>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane first" id="captain">
                                    <div class="row">
                                        <div class="col-sm-12 sexSelectionContainer">
                                            <div class="col-xs-6">
                                                <h4 class="info-text">{{ trans(config('app.directory_name') . '/lp1.form.man_looking_for_woman') }}</h4>
                                                <div class="choice active" data-toggle="wizard-radio">
                                                    <input type="radio" name="lookingFor" value="male-female"
                                                           checked="checked">
                                                    <div class="icon">
                                                        <img src="/lps/t1/assets/img/woman-2.jpg"
                                                             style="border-radius: 50%"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <h4 class="info-text">{{ trans(config('app.directory_name') . '/lp1.form.woman_looking_for_man') }}</h4>
                                                <div class="choice" data-toggle="wizard-radio">
                                                    <input type="radio" name="lookingFor" value="female-male">
                                                    <div class="icon">
                                                        <img src="/lps/t1/assets/img/man-2.jpg"
                                                             style="border-radius: 50%"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane second" id="details">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4 class="info-text">Maak GRATIS een account.</h4>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">email</i>
													</span>
                                                <div
                                                    class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}">
                                                    <label
                                                        class="control-label">{!! trans(config('app.directory_name') . '/lp1.form.email_short') !!}</label>
                                                    <input name="email"
                                                           type="text"
                                                           class="form-control"
                                                           value="{{ old('email') }}">
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">label</i>
													</span>
                                                <div
                                                    class="form-group label-floating {{ $errors->has('username') ? ' has-error' : '' }}"
                                                >
                                                    <label
                                                        class="control-label">{{ trans(config('app.directory_name') . '/lp1.form.username') }}</label>
                                                    <input name="username"
                                                           type="text"
                                                           class="form-control"
                                                           value="{{ old('username') }}">
                                                    @if ($errors->has('username'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('username') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">lock_outline</i>
													</span>
                                                <div
                                                    class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}"
                                                >
                                                    <label
                                                        class="control-label">{{ trans(config('app.directory_name') . '/lp1.form.password') }}</label>
                                                    <input name="password"
                                                           type="text"
                                                           class="form-control">
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-xs-12">
                                            <p class="mt-3"
                                               style="font-size: 1.3rem; text-align: justify; margin-bottom: 0; margin-top: 5px">
                                                {!! trans(config('app.directory_name') . '/lp1.form.register_info',
                                                        [
                                                            'privacyRoute' => route('privacy.show'),
                                                            'tacRoute' => route('tac.show'),
                                                        ]
                                                    )
                                                !!}
                                            </p>

                                            <div
                                                style="margin-top: 10px; text-align: left; white-space: nowrap; overflow: hidden;">
                                                <img src="{{ asset('img/safe.jpg') }}" style="width: 31px">
                                                <p style="font-size: 1.3rem; display: inline-block">
                                                    Uw persoonlijke data wordt absoluut vertrouwlijk behandeld.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wizard-footer">
                                <div class="pull-right">
                                    <input type='button' class='btn btn-next btn-fill btn-danger btn-wd' name='next'
                                           value='Volgende'/>
                                    <input type='button'
                                           class='btn btn-finish btn-fill btn-danger btn-wd JS--register-button'
                                           name='finish' value='Gratis Inschrijven'/>
                                </div>
                                <div class="pull-left">
                                    <input type='button' class='btn btn-previous btn-fill btn-default btn-wd'
                                           name='previous' value='<'/>

                                    <div class="footer-checkbox">
                                        <div class="col-sm-12 text-center">
                                            <div>Heb je al een account?</div>
                                            <a href="https://altijdsex.nl/login" class="btn btn-sm">Login</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div> <!-- wizard container -->
            </div>
        </div> <!-- row -->
    </div> <!--  big container -->

    <div class="footer">
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">
                <h3>Contact vrouwen</h3>
                <p style="font-size: 18px; font-weight: 300; text-align: justify;">Online chatten begint op een
                    datingsite, zo ook het contact met vrouwen. Sex chat en online chatten, in het bijzonder anoniem
                    geil chatten, zijn bijzonder goede manieren om je toekomstige bedpartner beter te leren kennen. Het
                    fijne is dat je nog weinig van jezelf bloot hoeft te geven, maar wel kunt uitvinden of iemand bij je
                    past. Sekscontact gaat vaak verder dan gewoon kennismaken, aangezien je toch enige verbinding wilt
                    hebben met degene waar je na de sex chat mee het bed in duikt.</p>
                <h3>Vreemdgaan</h3>
                <p style="font-size: 18px; font-weight: 300; text-align: justify;">Vind een vrouw om vreemd mee te gaan,
                    klinkt makkelijker dan het is. Hoe doe je dat eigenlijk en hoeveel vrouwen zoeken mannen voor
                    dezelfde redenen? Vrouw zoekt man komt net zo vaak voor als man zoekt vrouw óók als het om
                    vreemdgaan gaat. Belangrijk is dat dit geheel discreet gebeurd, vandaar dat de aanmelding geheel
                    anoniem verloopt en er geen persoonlijke gegevens worden vastgelegd. Dit zodat jij in alle rust kan
                    chatten met Nederlanders!</p>
                <h3>Vrouw vinden</h3>
                <p style="font-size: 18px; font-weight: 300; text-align: justify; margin-bottom: 30px">Na het gratis
                    registreren kun je eenvoudig op zoek gaan naar sekscontact met vrouwen online. Of je nu een oudere
                    vrouw zoekt, singles of gebonden mensen. Na inschrijving kun je met je gratis credit direct
                    sekscontact leggen en online chatten op deze datingsite voor sekscontacten!</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center" style="margin-bottom: 30px">
            <a style="color: #337ab7" href="{{ route('tac.show') }}">{{ trans(config('app.directory_name') . '/footer.tac') }}</a> - <a
                style="color: #337ab7" href="{{ route('contact.get') }}">{{ trans(config('app.directory_name') . '/footer.contact') }}</a>
        </div>
    </div>
</div>

</body>
<!--   Core JS Files   -->
<script src="/lps/t1/assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="/lps/t1/assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/lps/t1/assets/js/jquery.bootstrap.js" type="text/javascript"></script>

<!--  Plugin for the Wizard -->
<script src="/lps/t1/assets/js/material-bootstrap-wizard.js?v=11"></script>

<script src="/lps/t1/assets/js/jquery.validate.min.js"></script>
<script src="/lps/t1/assets/js/jquery.validate.min.js"></script>
<script src="{{ mix('js/lps/ads/lp1.js') }}"></script>
<script>


    /*
     * Application namespace
     *
     * Data that needs to be passed from the PHP application to JS
     * can be defined here
     */
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

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                timer = 0;
            }
        }, 1000);
    }

    window.onload = function () {
        var fiveMinutes = 60 * 5,
            display = document.querySelector('#time');
        startTimer(fiveMinutes, display);
    };
</script>
</html>
