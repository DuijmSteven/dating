<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>De beste datingsite voor sex dating | Altijdsex.nl</title>

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
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"/>

    <!-- CSS Files -->
    <link href="/lps/t1/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/lps/t1/assets/css/material-bootstrap-wizard.css?v=13" rel="stylesheet"/>

    @if(config('app.env') === 'local')
        <script src="https://www.google.com/recaptcha/api.js?render=6Lcb0N8UAAAAADUTgOIB9jcrz2xM60BPNjeK3qWL"></script>

    @elseif(config('app.env') === 'staging')
        <script src="https://www.google.com/recaptcha/api.js?render=6Ldx0N8UAAAAABj1wlIcdnxtgCxrprg3DPMsDtkj"></script>

        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('6Ldx0N8UAAAAABj1wlIcdnxtgCxrprg3DPMsDtkj\n', {action: 'homepage'}).then(function (token) {
                });
            });
        </script>
    @else
        <script src="https://www.google.com/recaptcha/api.js?render=6LdHptgUAAAAACP5lA0778MuyBsjs6oEnQcWo0T1"></script>

        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('6LdHptgUAAAAACP5lA0778MuyBsjs6oEnQcWo0T1', {action: 'homepage'}).then(function (token) {
                });
            });
        </script>
    @endif
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
                <img src="/img/site_logos/Altijdsex_Logo_Small_Pos.svg" width="200px">
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

                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                            <input type="hidden" name="action" value="validate_captcha">

                            <input id="userFingerprintInput" type="hidden" name="user_fingerprint" value="">

                            @if(isset($mediaId))
                                <input type="hidden" name="mediaId" value="{{ $mediaId }}">
                            @endif

                            @if(isset($clickId))
                                <input type="hidden" name="clickId" value="{{ $clickId }}">
                                <input type="hidden" name="affiliate" value="{{ $affiliate }}">
                            @endif

                            <div class="wizard-header">
                                <h3 class="wizard-title">
                                    Beperkte gratis inschrijving!
                                </h3>
                                <h5>Er zijn nog <span id="countdown"
                                                      style="color: #f44336; font-weight: bold">200</span> gratis
                                    plekken beschikbaar</h5>

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
                                                    <h4 class="info-text">{{ @trans('lp1.form.man_looking_for_woman') }}</h4>
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
                                                    <h4 class="info-text">{{ @trans('lp1.form.woman_looking_for_man') }}</h4>
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
                                                        class="control-label">{!! @trans('lp1.form.email_short') !!}</label>
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
                                                            class="control-label">{{ @trans('lp1.form.username') }}</label>
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
                                                            class="control-label">{{ @trans('lp1.form.password') }}</label>
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

</body>
<!--   Core JS Files   -->
<script src="/lps/t1/assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="/lps/t1/assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/lps/t1/assets/js/jquery.bootstrap.js" type="text/javascript"></script>

<!--  Plugin for the Wizard -->
<script src="/lps/t1/assets/js/material-bootstrap-wizard.js?v=11"></script>

<script src="/lps/t1/assets/js/jquery.validate.min.js"></script>
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

    $('.JS--prevent-default__click').click(function (event) {
        event.preventDefault();
    });

    $('.JS--register-button').click(function (event) {
        grecaptcha.execute(DP.recaptchaKey, {action: 'register'}).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;

            $('#JS--registrationForm').submit();
        });
    });

    $(window).on('load', function () {
        setTimeout(() => {
            // Create a new ClientJS object
            var client = new ClientJS();

            // Get the client's fingerprint id
            var fingerprint = client.getFingerprint();

            $('#userFingerprintInput').val(fingerprint)
        }, 100);
    });

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
