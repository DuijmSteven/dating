<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>De beste datingsite voor sex dating | Datevrij.nl</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>

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
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"/>

    <!-- CSS Files -->
    <link href="/lps/t1/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/lps/t1/assets/css/material-bootstrap-wizard.css?v=13" rel="stylesheet"/>

    @include('frontend.landing-pages.common-google-captcha-part')

    <style>
        body {
            /*font-size: 1.9rem;*/
            font-weight: 300;

            font-family: 'Fira Sans', sans-serif;
        }

        .logo-container {
            position: relative;
            z-index: 100;
        }

        .wizard-card[data-color="red"] .moving-tab {
            background-color: #e4543a !important;
        }

        .btn.btn-danger {
            background-color: #e4543a !important;
        }

        .wizard-card[data-color="red"] .choice:hover .icon, .wizard-card[data-color="red"] .choice.active .icon {
            border-color: #e4543a !important;
            color: #e4543a !important;
        }
    </style>
</head>

<body class="adsLp2">
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
                <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/Datevrij_Logo_Small_Pos.png') !!}" width="200px">
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

                            <input type="hidden" name="registration_lp" value="{{ \App\LP::ADS_LP_2 }}">

                            @include('frontend.landing-pages.common-registration-form-part')

                            <div class="wizard-header">
                                <h3 class="wizard-title">
                                    Beperkte gratis inschrijving!
                                </h3>
                                <h5>Er zijn nog <span id="countdown"
                                                      style="color: #f44336; font-weight: bold">200</span> gratis
                                    plekken beschikbaar</h5>

                                @if(session()->has('recaptchaFailed') && session()->get('recaptchaFailed'))
                                    <div class="captchaFailed">
                                        {{ trans(config('app.directory_name') . '/lp1.captcha_failed_message') }}
                                    </div>
                                @endif

                                @if ($errors->first('fingerprintExists'))
                                    <div class="captchaFailed">
                                        Het ziet uit als je al een account heb! Als dat niet waar is neem contact op met de helpdesk.
                                    </div>
                                @endif
                            </div>
                            <div class="wizard-navigation">
                                <div class="progress" style="width: 95%; margin: 0 auto">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: 25%; background-color: #f44336;" aria-valuenow="25"
                                         aria-valuemin="0" aria-valuemax="100">25%
                                    </div>
                                </div>
                                <ul style="display: none">
                                    <li><a href="#step1" data-step="1" data-toggle="tab">25%</a></li>
                                    <li><a href="#step2" data-step="2" data-toggle="tab">50%</a></li>
                                    <li><a href="#step3" data-step="3" data-toggle="tab">75%</a></li>
                                    <li><a href="#step4" data-step="4" data-toggle="tab">100%</a></li>
                                </ul>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane" id="step1">
                                    <div class="row">
                                        <div class="col-sm-12 sexSelectionContainer">
                                            <div class="col-xs-6">
                                                <div class="sexChoiceWrapper choice" data-toggle="wizard-radio-lp2">
                                                    <h4 class="info-text">{{ trans(config('app.directory_name') . '/lp1.form.man_looking_for_woman') }}</h4>
                                                    <div class="choice active" data-toggle="wizard-radio-lp2">
                                                        <input type="radio" name="lookingFor" value="male-female"
                                                               checked="checked">
                                                        <div class="icon">
                                                            <img src="/lps/t1/assets/img/woman-2.jpg"
                                                                 style="border-radius: 50%"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="sexChoiceWrapper choice" data-toggle="wizard-radio-lp2">
                                                    <h4 class="info-text">{{ trans(config('app.directory_name') . '/lp1.form.woman_looking_for_man') }}</h4>
                                                    <div class="choice" data-toggle="wizard-radio-lp2">
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
                                </div>
                                <div class="tab-pane" id="step2">
                                    <div class="row">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step3">
                                    <div class="row">
                                        <div class="col-sm-12 sexSelectionContainer">
                                            <div class="col-sm-12">
                                                <div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">label</i>
													</span>
                                                    <div
                                                        class="form-group label-floating {{ $errors->has('username') ? ' has-error' : '' }}">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step4">
                                    <div class="row">
                                        <div class="col-sm-12 sexSelectionContainer">
                                            <div class="col-sm-12">
                                                <div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">lock_outline</i>
													</span>
                                                    <div
                                                        class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
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
                                            <a href="https://datevrij.nl/login" class="btn btn-sm">Login</a>
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

</body>
<!--   Core JS Files   -->
<script src="/lps/t1/assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="/lps/t1/assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/lps/t1/assets/js/jquery.bootstrap.js" type="text/javascript"></script>

<!--  Plugin for the Wizard -->
<script src="/lps/t1/assets/js/material-bootstrap-wizard.js?v=11"></script>

<script src="/lps/t1/assets/js/jquery.validate.min.js"></script>
<script src="{{ mix('js/lps/ads/lp2.js') }}"></script>
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

    var count = 200;
    var ms = 200;
    var step = 300;
    var counter = setTimeout(timer, ms); //1000 will  run it every 1 second

    function timer() {
        count = count - 1;
        document.getElementById("countdown").innerHTML = count; // watch for spelling

        if (count % 10 === 0) {
            ms = ms + step;
        }

        counter = setTimeout(timer, ms);

        if (count === 0) {
            count = 200;
        }
    }

    $(window).load(function () {
        if ($('#step2').find('.help-block').length !== 0) {
            setTimeout("$('a[href=\"#step2\"]').click()", 10);
        } else if ($('#step3').find('.help-block').length !== 0) {
            setTimeout("$('a[href=\"#step3\"]').click()", 10);
        } else if ($('#step4').find('.help-block').length !== 0) {
            setTimeout("$('a[href=\"#step4\"]').click()", 10);
        }


        //lp2
        $('[data-toggle="wizard-radio-lp2"]').click(function () {
            wizard = $(this).closest('.wizard-card');
            wizard.find('[data-toggle="wizard-radio-lp2"]').removeClass('active');
            $(this).addClass('active');
            $(wizard).find('[type="radio"]').prop('checked', false).removeAttr('checked');
            $(this).find('[type="radio"]').prop('checked', true).attr('checked', 'checked');
            setTimeout("$('a[href=\"#step2\"]').click()", 150);
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

            const step = $(e.target).data('step');

            if (step === 1) {
                $('.progress-bar').css({width: 25 + '%'});
                $('.progress-bar').text("25%");
            } else if (step === 2) {
                $('.progress-bar').css({width: 50 + '%'});
                $('.progress-bar').text("50%");
            } else if (step === 3) {
                $('.progress-bar').css({width: 75 + '%'});
                $('.progress-bar').text("75%");
            } else if (step === 4) {
                $('.progress-bar').css({width: 100 + '%'});
                $('.progress-bar').text("100%");
            }

        })
    });
</script>
</html>
