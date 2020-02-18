<!doctype html>
<html lang="nl">
@include('frontend.layouts.default.partials.head')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>


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
        margin-bottom: 10rem;
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
        color: #ef4f2b;
    }

    .recent-container h5.small {
        font-size: 1.6rem;
        font-weight: 700;
        color: #ef4f2b;
        letter-spacing: .1em;
        margin-top: 1.375rem;
    }

    form label {
        color: #ef4f2b;
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
        background-color: #2e3142;
        border-color: #2e3142;
    }

    .btn-register-login,
    .btn-accept-cookies {
        background-color: #ef4f2b;
        border-color: #ef4f2b;
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

    .btn-register-login:hover {
        background-color: #f96431;
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
        color: #ef4f2b;
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
        border-radius: 50%;
        object-fit: cover;
        transform: translateY(-0.5rem);
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
        top: 200px;
        z-index: 1000;
    }

    @media screen and (max-width: 1000px) {
        .form-wrapper {
            width: 300px;
        }
    }

    @media screen and (max-width: 767px) {
        .form-wrapper {
            width: 100%;
            top: 390px;
            z-index: 1000;
            left: 50%;
            margin-left: -50%;
            right: initial;
        }
    }

    video {
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
        color: #ef4f2b;
        margin: 0;
        position: absolute;
        bottom: 258px;
        left: 15px;
        text-shadow: 2px 2px #564f4f;
        width: 412px;
        font-weight: 400;
        text-align: left;
    }

    @media screen and (max-width: 1000px) {
        h1 {
            width: 370px;32
            bottom: 150px;
        }
    }

    @media screen and (max-width: 767px) {
        h1 {
            width: 93%;
            text-align: center;
            left: initial;
            bottom: initial;
            top: 225px;
            font-size: 29px;
        }
    }

    .welcome-container {
        margin-top: 18rem;
    }

    @media screen and (max-width: 1000px) {
        .welcome-container {
            margin-top: 15rem;
        }
    }

    @media screen and (max-width: 767px) {
        .welcome-container {
            margin-top: 340px;
        }

        .welcome-container.withRegistrationForm {
            margin-top: 650px;
        }
    }

    .recent-container {
        margin-top: 10rem;
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
        background-color: red;
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
        width: 286px;
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

    @media screen and (max-width: 767px) {
        .topQuote {
            display: none;
        }
    }

</style>
<body class="landingPage">
<header>
    <div class="container" style="z-index: 50; position:relative;">
        <div class="navbar-brand">
            <img src="img/site_logos/Altijdsex_LogoBig_Pos.svg" title="Altijdsex.nl Logo" alt="Altijdsex.nl Logo"/>
        </div>
    </div>
</header>
<main>
    <div class="vh-70 bg-image text-center JS--header" style="background-image: url('img/lp/dating-bg.jpg'); position: relative">
        <div class="container" style="position:relative; height: 100%">
            <h1 style="text-transform: none">{{ @trans('lp1.heading') }}</h1>

            <video class="img-fluid screenCapturePreview" playsinline="" autoplay="" muted="" loop="" no-controls=""
                   style="max-width: 80%">--}}
                <source type="video/mp4" src="{{ asset('img/lp/altijdSex-preview.mp4') }}">
            </video>

            <div class="JS--form-wrapper form-wrapper hidden">
                <div class="form-box">
                    <form class="test--RegistrationForm" method="POST" action="{{ route('register.post') }}"
                          id="JS--registrationForm"
                          style="display: none"
                    >
                        {{ csrf_field() }}
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
                            <div class="form-group col-md-12 {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="name">{{ @trans('lp1.form.username') }}</label>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       placeholder="{{ @trans('lp1.form.username') }}"
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
                            <div class="form-group col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">{{ @trans('lp1.form.password') }}</label>
                                <input type="text" class="form-control" id="password" name="password"
                                       placeholder="{{ @trans('lp1.form.password') }}" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">{{ @trans('lp1.form.email') }}</label>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       placeholder="{{ @trans('lp1.form.email_placeholder') }}"
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
{{--                        <div class="form-row">--}}
{{--                            <div class="form-group col-md-12 {{ $errors->has('city') ? ' has-error' : '' }}">--}}
{{--                                <label for="city">{{ @trans('lp1.form.city') }}</label>--}}
{{--                                <input type="text"--}}
{{--                                       class="JS--autoCompleteCites form-control"--}}
{{--                                       id="city"--}}
{{--                                       name="city"--}}
{{--                                       placeholder="{{ @trans('lp1.form.city') }}"--}}
{{--                                       value="{{ old('city') }}"--}}
{{--                                       required--}}
{{--                                >--}}
{{--                                @if ($errors->has('city'))--}}
{{--                                    <span class="help-block">--}}
{{--                                        <strong>{!! $errors->first('city', ':message') !!}</strong>--}}
{{--                                    </span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-row">--}}
{{--                            <div class="form-group col-md-12 {{ $errors->has('dob') ? ' has-error' : '' }}">--}}
{{--                                <label for="password">{{ @trans('lp1.form.dob') }}</label>--}}
{{--                                <div--}}
{{--                                    class="input-group date dateOfBirthGroup"--}}
{{--                                >--}}
{{--                                    <input id="datepicker_dob" type="text" class="form-control" name="dob" required--}}
{{--                                           value="{{ old('dob') }}">--}}
{{--                                    <div class="input-group-addon">--}}
{{--                                        <i class="material-icons calendar">--}}
{{--                                            calendar_today--}}
{{--                                        </i>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                @if ($errors->has('dob'))--}}
{{--                                    <span class="help-block">--}}
{{--                                        <strong>{{ $errors->first('dob', ':message') }}</strong>--}}
{{--                                    </span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-row">--}}
{{--                            <div class="form-group col-md-12">--}}
{{--                                <label for="password-confirm">{{ @trans('lp1.form.password_confirmation') }}</label>--}}
{{--                                <input type="password" class="form-control" id="password-confirm"--}}
{{--                                       name="password_confirmation" placeholder="{{ @trans('lp1.form.password') }}"--}}
{{--                                       required>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-row">
                            <div class="col-sm-12 submit">
                                <button type="submit"
                                        class="btn btn-secondary btn-lg btn-block">{{ @trans('lp1.form.register_now') }}</button>
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
                                <button id="JS--loginButton" type="button"
                                        class="btn btn-block btn-lg btn-register-login loginButton"
                                >
                                    {{ @trans('lp1.form.login') }}
                                </button>
                            </div>
                        </div>


                        <div class="freeCreditsUsp">
                            <span>{{ trans('lp1.free_credits_usp') }}</span>
                        </div>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('login.post') }}"
                        class="test--LoginForm"
                        id="JS--loginForm"
                        style="display: none"
                    >
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-12 {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="login-username">{{ @trans('lp1.form.username') }}</label>
                                <input type="text" class="form-control" id="login-username" name="username"
                                       placeholder="{{ @trans('lp1.form.username') }}" value="{{ old('username') }}"
                                       required autofocus>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="login-password">{{ @trans('lp1.form.password') }}</label>
                                <input type="password" class="form-control" id="login-password" name="password"
                                       placeholder="{{ @trans('lp1.form.password') }}" required>
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
                                <button id="JS--registerButton" type="button"
                                        class="btn btn-block btn-lg btn-register-login registerButton">{{ @trans('lp1.form.register') }}
                                </button>
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
                </div>

                <div class="topQuote">
                    <span class="quoteMark">"</span><span class="quoteText">{{ trans('lp1.top_quote') }}</span><span class="quoteMark">"</span>
                    <div class="signature">- Tom, 41</div>
                </div>
            </div>
        </div>

    </div>

    <div class="container welcome-container text-center JS--welcome-container">
        <h3>{{ @trans('lp1.welcome') }}</h3>
        <div>
            <p class="mt-5">{{ @trans('lp1.welcome_text') }}</p>
        </div>
        <div class="row icons" style="margin-top: 75px">
            <div class="col-md-4 col-sm-12">
                <i class="material-icons">
                    how_to_reg
                </i>
                <h4>{{ @trans('lp1.usps.free_access.title') }}</h4>
                <p style="text-align: center">{{ @trans('lp1.usps.free_access.text') }}</p>
            </div>
            <div class="col-md-4 col-sm-12">
                <i class="material-icons">
                    chat
                </i>                <h4>{{ @trans('lp1.usps.live_chat.title') }}</h4>
                <p style="text-align: center">{{ @trans('lp1.usps.live_chat.text') }}</p>
            </div>
            <div class="col-md-4 col-sm-12">
                <i class="material-icons">
                    vpn_lock
                </i>
                <h4>{{ @trans('lp1.usps.privacy_anonymity.title') }}</h4>
                <p style="text-align: center">{{ @trans('lp1.usps.privacy_anonymity.text') }}</p>
            </div>
        </div>
    </div>
    <div class="container recent-container text-center currentMembers">
        <h3 style="margin-bottom: 40px">{{ @trans('lp1.a_group_of_members') }}</h3>
        <div class="row mt-5">
            @foreach ($users as $user)
                <div class="col-lg-2 col-md-4 col-sm-12 mb-4">
                    <a href="{{ route('users.show', ['username' => $user->getUsername()]) }}">
                        <img
                            src="{{ \StorageHelper::profileImageUrl($user, true) }}"
                            class="roundImage"
                            alt="profile-image"
                        >
                    </a>
                    <h5 class="small"
                        style="font-size: 1.9rem; width: 170px; overflow: hidden; text-overflow: ellipsis; margin: 10px auto 5px auto">{{ $user->username }}</h5>
                    <p class="offset-0 user-info" style="line-height: 14px; text-align: center">
                        <small class="text-muted" style="font-size: 1.4rem">{{ @trans('lp1.age') }}
                            : {{ $user->meta->dob->diffInYears($carbonNow) }}, {{ $user->meta->city }}</small>
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container text-center secondWelcome" style="margin-bottom: 10rem">
        <h3 style="margin-bottom: 40px">{{ @trans('lp1.online_sex_dating') }}</h3>
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

    <div class="container testimonials-container text-center">
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
                            <blockquote class="quote">
                                <p class="q">
                                    <q>{{ $testimonial['quote'] }}</q>
                                </p>

                                <p class="cite">
                                    <cite class="text-muted">
                                        - {{ $testimonial['names'] }}
                                    </cite>
                                </p>
                            </blockquote>
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
                <a href="{{ route('landing-page.show', ['locale' => 'nl']) }}">
                    <div class="flagImageWrapper">
                        <img class="flagImage" src="{{ asset('img/flags/nl.png') }}" alt="">
                    </div>
                </a> |
                <a href="{{ route('landing-page.show', ['locale' => 'en']) }}">
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
        locale: '{{ app()->getLocale() }}'
    };

</script>
<script src="{{ mix('js/lp.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script>$('.carousel').carousel()</script>
</body>
