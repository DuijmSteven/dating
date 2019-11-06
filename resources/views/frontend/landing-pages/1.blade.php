{{--@extends('frontend.layouts.default.layout')

@section('content')

LP 1

@endsection--}}
        <!doctype html>
<html lang="en">
@include('frontend.layouts.default.partials.head')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
      integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">


<style>
    .bg-image {
        background-position: center center;
        background-size: cover;
    }

    .vh-70 {
        height: 70vh;
    }

    .vh-70 .container {
        position: absolute;
        top: 47%;
        left: 0;
        right: 0;
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    h1 {
        font-family: comfortaa, sans-serif;
        font-size: 2.8rem;
        letter-spacing: .02em;
        text-transform: uppercase;
        font-weight: 700;
        color: #ef4f2b;
        margin: 0;
    }

    .form-container {
        margin-top: -13rem;
        max-width: 1200px;
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px;
    }

    .form-container .form-box {
        padding: 40px;
        background-color: #ffffff;
        box-shadow: 0px 22px 44px 0px rgba(46, 49, 66, 0.3);
        border-radius: 2px;
    }

    .welcome-container, .recent-container {
        margin-top: 10rem;
    }

    .recent-container, .testimonials-container {
        margin-bottom: 10rem;
    }

    .welcome-container h3, .recent-container h3, .testimonials-container h3 {
        font-family: comfortaa, sans-serif;
        font-size: 3.125rem;
        text-transform: uppercase;
        color: #2e3142;
    }

    .welcome-container p {
        font-size: 1.5rem;
    }

    .welcome-container h4 {
        font-size: 2.5rem;
        margin: 1rem;
    }

    .icons i {
        font-size: 8em;
        color: #ef4f2b;
    }

    .recent-container h5.small {
        font-size: 1.4rem;
        font-weight: 700;
        color: #ef4f2b;
        letter-spacing: .1em;
        text-transform: uppercase;
        margin-top: 1.375rem;
    }

    .recent-container img {
        max-width: 170px;
    }

    form label {
        font-family: comfortaa, sans-serif;
        color: #ef4f2b;
        font-size: 18px;
    }

    .form-control {
        font-size: 1.4rem;
    }

    form .submit {
        margin: 0 auto;
    }

    .text-muted {
        color: #b6b6b6;
    }

    small, .small {
        font-size: 1.2rem;
        line-height: 1.2;
    }

    .navbar-brand {
        text-align: left;
        margin-top: 2rem;
        margin-left: 0;
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

    .btn-register-login {
        background-color: #ef4f2b;
        border-color: #ef4f2b;
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

    footer {
        background-color: #2e3142;
        color: #ffffff;
        padding-top: 3rem;
    }

    footer h4 {
        color: #ef4f2b;
    }

    footer img {
        width: 110px;
        height: 110px;
        padding-top: 3rem;
    }

    footer .copyright {
        padding-bottom: 3rem;
    }

    footer .Footer__section-listItem a {
        color: #ffffff;
        font-size: 15px;
    }

    @media (min-width: 768px) {
        h1 {
            font-size: 6rem;
        }

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
    }

    .carousel-item img {
        border-radius: 50%;
    }

    .quote {
        font-style: italic;
        font-weight: 300;
        font-size: 16px;
        max-width: 700px;
        display: inline-block;
    }

    .dateOfBirthGroup .input-group-addon {
        text-align: center;
        width: 40px;
        padding-left: 0;
        padding-right: 0;
    }

    .currentMembers .imageWrapper {
        display: inline-block;
        overflow: hidden;
        border-radius: 50%;
        width: 170px;
        height: 170px;
    }

    .currentMembers .imageWrapper img {
        width: 170px;
    }

</style>
<body class="landingPage">
<header>
    <div class="container">
        <div class="navbar-brand">
            <img src="img/site_logos/Altijdsex_LogoBig_Pos.svg"/>
        </div>
    </div>
</header>
<main>
    <div class="vh-70 bg-image text-center" style="background-image: url(img/bg.jpg)">
        <div class="container">
            <h1>Meet your next love here!</h1>
        </div>
    </div>
    <div class="form-container">
        <div class="row">
            <div class="form-box col-md-4 offset-md-4 col-xs-12 offset-xs-0">
                <form method="POST" action="{{ route('register.post') }}" id="JS--registrationForm"
                      style="display: none">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="lookingFor">I am a:</label>
                            <select class="form-control" id="lookingFor" name="lookingFor">
                                <option value="male-female">Man looking for a Woman</option>
                                <option value="female-male">Woman looking for a Man</option>
                                <option value="female-female">Woman looking for a Woman</option>
                                <option value="male-male">Man looking for a Man</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="name">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                   value="{{ old('username') }}" required autofocus>
                            @if ($errors->has('username'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                   value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12{{ $errors->has('dob') ? ' has-error' : '' }}">
                            <label for="password">Date of birth</label>
                            <div
                                    class="input-group date dateOfBirthGroup"
                                    data-provide="datepicker"
                                    data-date-format="dd-mm-yyyy"
                                    data-date-start-view="decade"
                                    data-date-start-date="01-01-1900"
                            >
                                <input type="text" class="form-control" name="dob">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="password-confirm">Confirm Password</label>
                            <input type="password" class="form-control" id="password-confirm"
                                   name="password_confirmation" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 submit">
                            <button type="submit" class="btn btn-secondary btn-lg btn-block">Register Now!</button>
                        </div>
                    </div>
                    <p class="mt-3">By clicking on "Register Now!" you agree to our <a href="#">terms and conditions</a>.
                    </p>
                    <div class="form-row" style="margin-top: 20px">
                        <div class="col-sm-10">
                            <h4 class="mt-3">Have an account?</h4>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 submit">
                            <button id="JS--loginButton" type="button"
                                    class="btn btn-block btn-lg btn-register-login loginButton">Login
                            </button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('login.post') }}" id="JS--loginForm">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="form-group col-md-12{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="login-username">Username</label>
                            <input type="text" class="form-control" id="login-username" name="username"
                                   placeholder="Username" value="{{ old('username') }}" required autofocus>
                            @if ($errors->has('username'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="login-password">Password</label>
                            <input type="password" class="form-control" id="login-password" name="password"
                                   placeholder="Password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 submit">
                            <button type="submit" class="btn btn-secondary btn-lg btn-block">Login</button>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top: 20px">
                        <div class="col-sm-10">
                            <h4 class="mt-3">Don't have an account?</h4>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 submit">
                            <button id="JS--registerButton" type="button"
                                    class="btn btn-block btn-lg btn-register-login registerButton">Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container welcome-container text-center">
        <h3>Welcome!</h3>
        <p class="mt-5">Welcome to the leading dating site for singles interested in serious dating. Search our women
            personals and browse through 1000s of profiles. If you're looking for long term relationships with beautiful
            women or handsome men, you've come to the right place. Start interacting with singles via chat - join free
            now! </p>
        <div class="row icons" style="margin-top: 75px">
            <div class="col-md-4 col-sm-12">
                <i class="far fa-star"></i>
                <h4>Satisfied users</h4>
                <p>One of the best dating site in Netherlands</p>
            </div>
            <div class="col-md-4 col-sm-12">
                <i class="far fa-heart"></i>
                <h4>Find your love</h4>
                <p>Countless profiles for what you are looking for</p>
            </div>
            <div class="col-md-4 col-sm-12">
                <i class="far fa-handshake"></i>
                <h4>Privacy & Anonymity</h4>
                <p>Your data stay safe with us</p>
            </div>
        </div>
    </div>
    <div class="container recent-container text-center currentMembers">
        <h3>Een greep uit onze leden</h3>
        <div class="row mt-5">
            @foreach ($users as $user)
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <a href="{{ route('users.show', ['userId' => $user->getId()]) }}" class="imageWrapper">
                        <img
                            src="{{ \StorageHelper::profileImageUrl($user) }}"
                            class="img-circle img-wide"
                            alt=""
                        >
                    </a>
                    <h5 class="small">{{ ucfirst($user->username) }}</h5>
                    <p class="offset-0">
                        <small class="text-muted">Age: {{ $user->meta->dob->diffInYears($carbonNow) }}
                            , {{ $user->meta->city }}</small>
                    </p>
                    <a href="{{ route('users.show', ['userId' => $user->getId()])  }}" class="btn btn-lg btn-light">More
                        Info</a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container testimonials-container text-center">
        <h3>Success Stories</h3>
        <div id="carouselExampleControls" class="carousel slide mt-5">
            <div class="carousel-inner">
                @foreach($testimonials as $testimonial)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="text-center">
                            <img class="img-wide"
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
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</main>
<footer>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <h4>Wetenswardig</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="http://localhost:81/articles">Artikelen</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12">
                <h4>Klantenservice</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="http://localhost:81/articles">Veelgestelde vragen</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12">
                <h4>Over ons</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="http://localhost:81/articles">Bedrijfsgegevens</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12">
                <h4>Juridish</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="http://localhost:81/articles">Algemene Voorwarden</a>
                    </li>
                    <li class="Footer__section-listItem">
                        <a href="http://localhost:81/articles">Privacybeleid</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-12">
                <img src="img/site_logos/Altijdsex_LogoBig_Neg.svg"/>
            </div>
            <div class="col-md-12 copyright">
                <h5>Copyright © 2019 altijdsex.nl</h5>
            </div>
        </div>
    </div>
</footer>
<script>
    /*
     * Application namespace
     *
     * Data that needs to be passed from the PHP application to JS
     * can be defined here
     */
    var DP = {
        baseUrl: '{!! url('/') !!}'
    };
</script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script>$('.carousel').carousel()</script>
</body>
