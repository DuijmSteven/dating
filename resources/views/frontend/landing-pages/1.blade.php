<!doctype html>
<html lang="nl">
@include('frontend.layouts.default.partials.head')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>

@if(config('app.env') === 'local')
    <script src="https://www.google.com/recaptcha/api.js?render=6Lcb0N8UAAAAADUTgOIB9jcrz2xM60BPNjeK3qWL"></script>

@elseif(config('app.env') === 'staging')
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldx0N8UAAAAABj1wlIcdnxtgCxrprg3DPMsDtkj"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Ldx0N8UAAAAABj1wlIcdnxtgCxrprg3DPMsDtkj\n', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@else
    <script src="https://www.google.com/recaptcha/api.js?render=6LdHptgUAAAAACP5lA0778MuyBsjs6oEnQcWo0T1"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdHptgUAAAAACP5lA0778MuyBsjs6oEnQcWo0T1', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@endif
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "https://altijdsex.nl/",
      "contactPoint": [
        { "@type": "ContactPoint",
          "email": "info@altijdsex.nl",
          "contactType": "customer service"
        }
      ]
    }
</script>
<style>
    body {
        font-size: 1.9rem;
        font-weight: 300;
    }

    p {
        font-size: 1.9rem;
        font-weight: 300;
    }

    .bg-image {
        background-position: center center;
        background-size: cover;
    }

    .vh-70 {
        height: 70vh;
    }

    /*.vh-70 .container {*/
    /*    position: absolute;*/
    /*    top: 85%;*/
    /*    left: 0;*/
    /*    right: 0;*/
    /*    -moz-transform: translateY(-50%);*/
    /*    -ms-transform: translateY(-50%);*/
    /*    -o-transform: translateY(-50%);*/
    /*    -webkit-transform: translateY(-50%);*/
    /*    transform: translateY(-50%);*/
    /*}*/

    .recent-container, .testimonials-container {
        margin-bottom: 6rem;
    }

    .welcome-container h3, .recent-container h3, .testimonials-container h3, .secondWelcome h3 {
        font-family: comfortaa, sans-serif;
        font-size: 3.125rem;
        text-transform: uppercase;
        color: #2e3142;
    }

    p {
        text-align: justify;
    }

    /*.welcome-container p {*/
    /*    font-size: 1.5rem;*/
    /*}*/

    .welcome-container h4 {
        font-size: 2.5rem;
        margin: 1rem;
    }

    .icons i {
        font-size: 8em;
        color: #ce5338;
    }

    .recent-container h5.small {
        font-size: 1.6rem;
        font-weight: 700;
        color: #ce5338;
        letter-spacing: .1em;
        margin-top: 1.375rem;
    }

    form label {
        color: #ce5338;
        font-size: 1.6rem;
        letter-spacing: 0.2px;
        font-weight: 400;
        margin-bottom: 0px;
    }

    .form-control {
        font-size: 1.6rem;
    }

    form .submit {
        margin: 0 auto;
    }

    .text-muted {
        color: #b6b6b6;
    }

    small, .small {
        font-size: 1.6rem;
        line-height: 1.2;
    }

    .navbar-brand {
        text-align: left;
        margin-top: 2rem;
        margin-left: 0;
        padding-left: 0;
    }

    header {
        background: 0 0;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        z-index: 2;
    }

    header .container .btn {
        float: right;
        margin-top: 3rem;
        right: 5%;
    }

    .btn-secondary {
        background-color: #82554b !important;
        border-color: #82554b !important;
        color: #fff;
    }

    .btn-secondary:hover,
    .btn-secondary:focus,
    .btn-secondary:active {
        background-color: #98695f !important;
        border-color: #82554b !important;
        color: #fff;
    }

    .btn-register-login,
    .btn-accept-cookies {
        background-color: #ce5338;
        border-color: #ce5338;
        color: #fff;
    }

    .btn-forgot-password {
        background-color: #fff;
        border-color: #ccc;
        color: #555;
    }

    .btn-forgot-password:hover,
    .btn-forgot-password:focus,
    .btn-forgot-password:active {
        background-color: #eee;
        border-color: #ccc;
        color: #555;
    }

    .cookie-popup {
        position: fixed;
        background-color: #fff;
        left: 0;
        right: 0;
        bottom: 0;
        box-shadow: 20px 0 30px rgba(0, 0, 0, 0.4);
        font-size: 1.8rem;
        color: #555;
        padding: 15px;
        text-align: center;
        z-index: 2000;
    }

    @media screen and (min-width: 1100px) {
        .cookie-popup p {
            width: 1000px;
        }
    }

    .cookie-popup p {
        display: inline-block;
        margin-bottom: 10px;
        font-size: 1.6rem;
    }

    .cookie-popup button {
        display: inline-block;
        width: 100px;
        color: #fff;
    }

    .cookie-popup button:hover {
        display: inline-block;
        width: 100px;
        color: #fff;
    }

    .btn-register-login:hover, .btn-register-login:focus, .btn-register-login:active {
        background-color: #e4543a;
        border-color: #ce5338;
        color: #fff;
    }

    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%232e3142' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e");
    }

    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%232e3142' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e");
    }

    blockquote {
        border-left: none;
    }

    @media (max-width: 991px) {
        blockquote {
            padding: 11px 61px;
        }
    }

    footer {
        background-color: #2e3142;
        color: #ffffff;
        padding-top: 3rem;
    }

    footer h4 {
        color: #ce5338;
    }

    footer .Footer__logo img {
        width: 110px;
        height: 110px;
        padding-top: 3rem;
    }

    footer .copyright {
        padding-bottom: 3rem;
    }

    footer .Footer__section-listItem a {
        color: #ffffff;
        font-size: 1.7rem;
        text-decoration: none;
    }

    @media (min-width: 768px) {
        header .navbar-brand img {
            width: 150px;
            height: 150px;
        }

        header .container .btn {
            margin-top: 5rem;
        }

        .form-container .btn-lg, header .btn-lg {
            padding: 1rem 1.5rem;
            font-size: 1.5rem;
        }
    }

    @media (max-width: 767.98px) {
        .media-left, .media-right, .media-body {
            display: block;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .recent-container .col-sm-12 {
            margin-bottom: 1.5em;
        }

        .form-container {
            margin-top: 0rem;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        h1 {
            font-size: 4.8rem;
        }
    }

    .carousel-item img {
        border-radius: 50%;
    }

    .quote {
        font-style: italic;
        font-weight: 300;
        font-size: 1.6rem;
        max-width: 700px;
        display: inline-block;
    }

    .dateOfBirthGroup .input-group-addon {
        text-align: center;
        width: 40px;
        padding-left: 0;
        padding-right: 0;
    }

    .Footer__section-title {
        font-size: 1.8rem;
    }

    .btn-group-lg > .btn, .btn-lg {
        rou padding: .5rem 1rem;
        font-size: 1.7rem;
        line-height: 1.5;
        border-radius: .3rem;
    }

    /* btn-white */
    .btn.btn-white {
        color: #3C4857;
        background-color: #fff;
        border-color: #fff;
        box-shadow: 0 2px 2px 0 rgba(153, 153, 153, 0.14), 0 3px 1px -2px rgba(153, 153, 153, 0.2), 0 1px 5px 0 rgba(153, 153, 153, 0.12);
    }

    .btn.btn-white:focus,
    .btn.btn-white:active,
    .btn.btn-white:hover {
        box-shadow: 0 4px 4px 0 rgba(153, 153, 153, 0.24), 0 3px 1px -2px rgba(153, 153, 153, 0.3), 0 1px 5px 0 rgba(153, 153, 153, 0.32);
    }

    .material-icons.calendar {
        font-size: 16px;
        line-height: 15px;
    }

    .user-info {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .input-group .form-control {
        z-index: unset;
    }

    .Footer__logo-container {
        position: relative;
    }

    .language-selection {
        position: absolute;
        right: 0;
        bottom: 40px;
    }

    @media screen and (max-width: 700px) {
        .language-selection {
            bottom: -10px;
        }
    }

    .language-selection a {
        display: inline-block;
    }

    .language-selection a .flagImage {
        width: 40px;
    }

    .roundImage {
        width: 150px;
        height: 150px;
        border-radius: 3px;
        object-fit: cover;
        transform: translateY(-0.5rem);
    }

    @media screen and (max-width: 767px) {
        .roundImage {
            width: 120px;
            height: 120px;
        }
    }

    .testimonials-container .roundImage {
        width: 14rem;
        height: 14rem;
    }

    .datepicker {
        font-size: 1.7rem;
    }

    .screenCapturePreview {
        box-shadow: 6px 0 28px rgba(0, 0, 0, 0.3);
    }

    .form-box .form-row {
        text-align: left;
    }

    .form-container {
        margin-top: -4rem;
        max-width: 1200px;
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px;
    }

    .form-box {
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0px 22px 44px 0px rgba(46, 49, 66, 0.3);
        border-radius: 2px;
    }

    .form-wrapper {
        width: 400px;
        position: absolute;
        right: 15px;
        bottom: -20px;
        z-index: 1000;
    }

    @media screen and (max-width: 1000px) {
        .form-wrapper {
            width: 300px;
            height: fit-content;
        }
    }

    @media screen and (max-width: 767px) {
        .form-wrapper {
            width: 84%;
            top: 210px;
            bottom: initial;
            z-index: 1000;
            right: 0;
            height: fit-content;
        }
    }

    video {
        display: none;
        position: absolute;
        width: 468px;
        bottom: -111px;
        left: 15px;
    }

    @media screen and (max-width: 1000px) {
        video {
            width: 370px;
            bottom: -100px;
        }
    }

    @media screen and (max-width: 767px) {
        video {
            display: none;
        }
    }

    h1 {
        font-size: 4.2rem;
        letter-spacing: .02em;
        text-transform: uppercase;
        color: #ce5338;
        margin: 0;
        position: absolute;
        bottom: 258px;
        left: 15px;
        text-shadow: 1px 2px #5a1f12;
        width: 412px;
        font-weight: 400;
        text-align: left;
    }

    @media screen and (max-width: 1000px) {
        h1 {
            width: 370px;
            bottom: 150px;
        }
    }

    @media screen and (max-width: 767px) {
        h1 {
            width: 208px;
            text-align: center;
            right: 9px;
            top: 59px;
            font-size: 17px;
            left: initial;
            text-shadow: 0px 1px #4a4141;
            color: #ce5338;
        }
    }

    .currentMembers {
        margin-top: 50px;
    }

    @media screen and (max-width: 1000px) {
        .currentMembers {
            margin-top: 15rem;
        }
    }

    @media screen and (max-width: 767px) {
        .currentMembers {
            margin-top: 340px;
        }

        .currentMembers.withRegistrationForm {
            margin-top: 650px;
        }
    }

    .recent-container {
        margin-top: 5rem;
    }

    .ui-autocomplete.ui-widget.ui-widget-content {
        max-height: 600px;
        overflow: scroll;
        list-style: none;
        padding-left: 15px;
        position: relative;
        z-index: 3000;
        max-width: 250px;
    }

    .ui-autocomplete.ui-widget.ui-widget-content li {
        font-size: 1.8rem;
        color: #555;
    }

    .freeCreditsUsp {
        position: absolute;
        top: -15px;
        left: 50%;
        width: 254px;
        margin-left: -127px;
        background-color: #504c4c;
        border-radius: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 30px;
        font-size: 1.9rem;
        font-weight: 500;
        padding-left: 10px;
        padding-right: 10px;
        color: #fff;
    }

    .topQuote {
        position: absolute;
        top: -165px;
        left: -6px;
        font-size: 1.9rem;
        color: saddlebrown;
        font-weight: 400;
        width: 310px;
    }

    .topQuote .signature {
        position: absolute;
        right: 28px;
        bottom: -30px;
    }

    .topQuote .quoteMark {
        font-size: 3rem;
        line-height: 1.7rem;
        padding-left: 6px;
        padding-right: 6px;
    }

    .language-selection {
        top: 25px;
        right: 20px;
    }

    @media screen and (max-width: 767px) {
        .topQuote {
            display: none;
        }

        .col-xs-6 {
            width: 50% !important;
        }

        .language-selection.header {
            top: 10px;
            right: 20px;
        }
    }

    .scrollToRegistration:hover {
        text-decoration: none;
    }

    .recentUsername {
        font-size: 1.9rem;
        width: 170px;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 10px auto 5px auto;
    }

    @media screen and (max-width: 767px) {
        .recentUsername {
            width: 130px;
        }

        h3 {
            font-size: 26px !important;
        }
    }

    .form-wrapper form input {
        padding: 30px 20px;
        font-size: 20px;
    }

    @media screen and (max-width: 767px) {
        .form-wrapper form input {
            font-size: 16px;
            padding: 30px 20px 16px;
        }
    }

    .enhancedFormGroup {
        position: relative;

    }

    .enhancedFormGroup label {
        position: absolute;
        top: 21px;
        left: 28px;
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 83%;
        overflow: hidden;
        font-weight: 300;
    }

    .enhancedFormGroup.open label {
        top: -7px;
        left: 5px;
        background: #fff;
        padding: 0px 5px;
        font-size: 14px;
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 90%;
        overflow: hidden;
    }

    .enhancedFormGroup .formLabelSmall span {
        font-size: 13px;
    }

    .captchaFailed {
        color: #fff;
        font-weight: 400;
        text-align: center;
        background-color: #7d2c23;
        border-radius: 2px;
        padding: 10px;
    }

</style>
<body class="landingPage">
<header>
    <div class="container" style="z-index: 50; position:relative;">
        <div class="navbar-brand">
            <img src="img/site_logos/Altijdsex_LogoBig_Neg.svg" title="Altijdsex.nl Logo" alt="Altijdsex.nl Logo"/>
        </div>

        @php
            if (\Request::route()->getName() === 'landing-page.show-register') {
                $localeRouteName = 'landing-page.show-register';
            } else {
                $localeRouteName = 'landing-page.show-login';
            }
        @endphp

        <div class="language-selection header">
            <a href="{{ route($localeRouteName, ['locale' => 'nl']) }}">
                <div class="flagImageWrapper">
                    <img class="flagImage" src="{{ asset('img/flags/nl.png') }}" alt="">
                </div>
            </a> |
            <a href="{{ route($localeRouteName, ['locale' => 'en']) }}">
                <div class="flagImageWrapper">
                    <img class="flagImage" src="{{ asset('img/flags/uk.svg') }}" alt="">
                </div>
            </a>
        </div>
    </div>
</header>
<main>
    <div class="vh-70 bg-image text-center JS--header" style="background-image: url('img/lp/datingsite-lp-bg.jpg'); position: relative">
        <div class="container" style="position:relative; height: 100%">
            <h1 style="text-transform: none">{{ @trans('lp1.heading') }}</h1>

            <div class="JS--form-wrapper form-wrapper">
                <div class="form-box">
                    @if($formType === 'register')
                        <form class="test--RegistrationForm" method="POST" action="{{ route('register.post') }}"
                              id="JS--registrationForm"
                        >
                            {{ csrf_field() }}

                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                            <input type="hidden" name="action" value="validate_captcha">

                            @if(session()->has('recaptchaFailed') && session()->get('recaptchaFailed'))
                                <div class="captchaFailed">
                                    {{ @trans('lp1.captcha_failed_message') }}
                                </div>
                            @endif

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="lookingFor">{{ @trans('lp1.form.i_am') }}:</label>
                                    <select class="form-control" id="lookingFor" name="lookingFor">
                                        <option value="male-female">{{ @trans('lp1.form.man_looking_for_woman') }}</option>
                                        <option value="female-male">{{ @trans('lp1.form.woman_looking_for_man') }}</option>
                                        {{--                                <option value="female-female">{{ @trans('lp1.form.woman_looking_for_woman') }}</option>--}}
                                        {{--                                <option value="male-male">{{ @trans('lp1.form.man_looking_for_man') }}</option>--}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group enhancedFormGroup JS--enhancedFormGroup col-md-12 {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="formLabelSmall" for="email">{!! @trans('lp1.form.email') !!}</label>
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
                                <div class="form-group enhancedFormGroup JS--enhancedFormGroup col-md-12 {{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="name">{{ @trans('lp1.form.username') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           id="username"
                                           name="username"
                                           value="{{ old('username') }}"
                                           required
                                           autofocus
                                    >
                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group enhancedFormGroup JS--enhancedFormGroup col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
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
                                    <button type="button"
                                            class="JS--register-button btn btn-register-login btn-lg btn-block">{{ @trans('lp1.form.register_now') }}</button>
                                </div>

                                <div class="col-xs-12">
                                    <p class="mt-3" style="font-size: 1.3rem; text-align: justify">
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
                            </div>

                            <div class="form-row">
                                <div class="col-sm-10">
                                    <h4 class="mt-3">{{ @trans('lp1.form.have_an_account') }}</h4>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-12 submit">
                                    <a href="{{ route('landing-page.show-login') }}" type="button"
                                            class="btn btn-block btn-lg btn-secondary loginButton"
                                    >
                                        {{ @trans('lp1.form.login') }}
                                    </a>
                                </div>
                            </div>


                            <div class="freeCreditsUsp">
                                <span>{{ trans('lp1.free_credits_usp') }}</span>
                            </div>
                        </form>
                    @endif

                    @if($formType === 'login')
                        <form
                            method="POST"
                            action="{{ route('login.post') }}"
                            class="test--LoginForm"
                            id="JS--loginForm"
                        >
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="enhancedFormGroup JS--enhancedFormGroup form-group col-md-12 {{ $errors->has('identity') || $errors->has('username') || $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="login-identity">{{ @trans('lp1.form.identity') }}</label>
                                    <input type="text" class="form-control" id="login-identity" name="identity"
                                           value="{{ old('identity') }}"
                                           required autofocus
                                    >
                                    @if ($errors->has('identity') || $errors->has('username') || $errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('identity') }}</strong>
                                        </span>
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="enhancedFormGroup JS--enhancedFormGroup form-group col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="login-password">{{ @trans('lp1.form.password') }}</label>
                                    <input type="password" class="form-control" id="login-password" name="password"
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
                                            class="btn btn-secondary btn-lg btn-block">{{ @trans('lp1.form.login') }}</button>
                                </div>
                            </div>
                            <div class="form-row" style="margin-top: 20px">
                                <div class="col-sm-10">
                                    <h4 class="mt-3">{{ @trans('lp1.form.not_have_an_account') }}</h4>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-12 submit">
                                    <a href="{{ route('landing-page.show-register') }}" type="button"
                                            class="btn btn-block btn-lg btn-register-login registerButton">{{ @trans('lp1.form.register') }}
                                    </a>
                                </div>
                            </div>
                            <div class="form-row" style="margin-top: 20px">
                                <div class="col-sm-10">
                                    <h4 class="mt-3">{{ @trans('lp1.forgot_password') }}</h4>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-12">
                                    @include('frontend.components.button', [
                                        'url' => route('password.reset.get'),
                                        'buttonContext' => 'general',
                                        'buttonText' => trans('lp1.reset_password'),
                                        'buttonClasses' => 'btn btn-block btn-lg btn-forgot-password'
                                    ])
                                </div>
                            </div>
                        </form>
                    @endif
                </div>

{{--                <div class="topQuote">--}}
{{--                    <span class="quoteMark">"</span><span class="quoteText">{{ trans('lp1.top_quote') }}</span><span class="quoteMark">"</span>--}}
{{--                    <div class="signature">- Tom, 41</div>--}}
{{--                </div>--}}
            </div>
        </div>

    </div>

    <div class="container recent-container text-center currentMembers JS--currentMembers {{ $formType === 'register' ? 'withRegistrationForm' : '' }}">
        <h3 style="margin-bottom: 40px">{{ @trans('lp1.a_group_of_members') }}</h3>
        <div class="row mt-5">
            @foreach ($users as $user)
                <div class="col-xs-6 col-lg-2 col-md-4 col-sm-6 mb-4 {{ $loop->index > 5 ? 'hidden-xs' : '' }}">
                    <a href="#" class="scrollToRegistration">
                        <img
                            src="{{ \StorageHelper::profileImageUrl($user, true) }}"
                            class="roundImage"
                            alt="profile-image"
                        >
                        <h5 class="small recentUsername">{{ $user->username }}</h5>
                        <p class="offset-0 user-info" style="line-height: 14px; text-align: center">
                            <small class="text-muted" style="font-size: 1.4rem">{{ $user->meta->dob->diffInYears($carbonNow) }}, {{ $user->meta->city }}</small>
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container welcome-container text-center JS--welcome-container" style="margin-bottom: 8rem;">
        <h3>{{ @trans('lp1.welcome') }}</h3>
        <div>
            <p class="mt-4">{{ @trans('lp1.welcome_text') }}</p>
        </div>
        <div class="row icons" style="margin-top: 75px">
            <div class="col-xs-12 col-md-4 col-sm-12">
                <i class="material-icons">
                    how_to_reg
                </i>
                <h4>{{ @trans('lp1.usps.free_access.title') }}</h4>
                <p style="text-align: center">{{ @trans('lp1.usps.free_access.text') }}</p>
            </div>
            <div class="col-xs-12 col-md-4 col-sm-12">
                <i class="material-icons">
                    chat
                </i>                <h4>{{ @trans('lp1.usps.live_chat.title') }}</h4>
                <p style="text-align: center">{{ @trans('lp1.usps.live_chat.text') }}</p>
            </div>
            <div class="col-xs-12 col-md-4 col-sm-12">
                <i class="material-icons">
                    vpn_lock
                </i>
                <h4>{{ @trans('lp1.usps.privacy_anonymity.title') }}</h4>
                <p style="text-align: center">{{ @trans('lp1.usps.privacy_anonymity.text') }}</p>
            </div>
        </div>
    </div>

    <div class="container testimonials-container text-center" style="margin-bottom: 8rem;">
        <h3>{{ @trans('lp1.success_stories') }}</h3>
        <div id="carouselExampleControls" class="carousel slide mt-5">
            <div class="carousel-inner" style="padding-top: 15px">
                @foreach($testimonials as $testimonial)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="text-center">
                            <img class="roundImage"
                                 src="{{ $testimonial['imgSource'] }}"
                                 alt="">
                        </div>
                        <div class="text-center">
                            <blockquotZe class="quote">
                                <p class="q">
                                    <q>{{ $testimonial['quote'] }}</q>
                                </p>

                                <p class="cite">
                                    <cite class="text-muted">
                                        - {{ $testimonial['names'] }}
                                    </cite>
                                </p>
                            </blockquotZe>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">{{ @trans('lp1.previous') }}</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">{{ @trans('lp1.next') }}</span>
            </a>
        </div>
    </div>

    <div class="container text-center secondWelcome" style="margin-bottom: 10rem;">
        <h3 style="margin-bottom: 40px">{!! @trans('lp1.online_sex_dating') !!}</h3>
        <div class="mt-5">
            <p>
                {!! @trans(
                 'lp1.second_welcome',
                 [
                     'articlesRoute' => route('articles.overview'),
                 ]
             )
         !!}
            </p>
        </div>
    </div>

</main>
<footer class="Footer">
    <div class="container">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer_section">
                <h4 class="Footer__section-title">{{ @trans('footer.noteworthy') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('articles.overview') }}">{{ @trans('footer.articles') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">{{ @trans('footer.client_service') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('faq.show') }}">{{ @trans('footer.faq') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">{{ @trans('footer.about_us') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('contact.get') }}">{{ @trans('footer.company_data') }}</a>
                    </li>
                    <li class="Footer__section-listItem">
                        <a href="{{ route('contact.get') }}">{{ @trans('footer.contact') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">{{ @trans('footer.legal') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('tac.show') }}">{{ @trans('footer.tac') }}</a>
                    </li>
                    <li class="Footer__section-listItem">
                        <a href="{{ route('privacy.show') }}">{{ @trans('footer.privacy') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 text-center Footer__logo-container">
            <div class="Footer__logo">
                <img src="{!! asset('img/site_logos/Altijdsex_LogoBig_Neg.svg') !!}" alt="{{ config('app.name') }}">
            </div>
            <div class="col-md-12 copyright">
                <h5>{{ @trans('footer.copyright', ['currentYear' => $carbonNow->year]) }}</h5>
            </div>

            <div class="language-selection">
                <a href="{{ route($localeRouteName, ['locale' => 'nl']) }}">
                    <div class="flagImageWrapper">
                        <img class="flagImage" src="{{ asset('img/flags/nl.png') }}" alt="">
                    </div>
                </a> |
                <a href="{{ route($localeRouteName, ['locale' => 'en']) }}">
                    <div class="flagImageWrapper">
                        <img class="flagImage" src="{{ asset('img/flags/uk.svg') }}" alt="">
                    </div>
                </a>
            </div>
        </div>
    </div>
</footer>

<div class="cookie-popup hidden">
    <p>
        <strong>Altijdsex.nl</strong> maakt gebruik van cookies om de website continu te kunnen blijven verbeteren. Als
        je op “Akkoord”
        klikt of je registreert op deze website, ga je automatisch akkoord met het <a
            href="{{ route('privacy.show') }}">Privacy- en Cookiebeleid</a>
    </p>

    <div>
        <button type="button"
                class="btn btn-block btn-lg btn-accept-cookies JS--acceptCookies"
        >
            {{ @trans('lp1.accept') }}
        </button>
    </div>
</div>

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

</script>
<script src="{{ mix('js/lp.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script>$('.carousel').carousel()</script>
</body>
