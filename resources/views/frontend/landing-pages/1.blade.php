{{--@extends('frontend.layouts.default.layout')

@section('content')

LP 1

@endsection--}}
<!doctype html>
<html lang="en">
@include('frontend.layouts.default.partials.head')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
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
        font-family: comfortaa,sans-serif;
        font-size: 2.8rem;
        letter-spacing: .02em;
        text-transform: uppercase;
        font-weight: 700;
        color: #ef4f2b;
        margin: 0;
    }
    .form-container {
        margin-top: -20rem;
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
        font-family: comfortaa,sans-serif;
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
        font-family: comfortaa,sans-serif;
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
        .carousel-control-prev {
            left: 20%;
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
</style>
<body>
    <header>
        <div class="container">
            <div class="navbar-brand">
                <img src="img/site_logos/Altijdsex_LogoBig_Pos.svg" />
            </div>
            <a href="#" class="btn btn-secondary btn-lg">Login</a>
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
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputLookingFor">I am a:</label>
                                <select class="form-control" id="inputLookingFor">
                                    <option>Man looking for a Woman</option>
                                    <option>Woman looking for a Man</option>
                                    <option>Woman looking for a Woman</option>
                                    <option>Man looking for a Man</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputName">Name</label>
                                <input type="text" class="form-control" id="inputName" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputEmail">Email</label>
                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Password</label>
                                <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-10 submit">
                                <button type="submit" class="btn btn-secondary btn-lg btn-block">Register Now!</button>
                            </div>
                        </div>
                        <p class="mt-3">By clicking on "Register Now!" you agree to our <a href="#">terms and conditions</a>.</p>
                    </form>
                </div>
            </div>
        </div>
        <div class="container welcome-container text-center">
            <h3>Welcome!</h3>
            <p class="mt-5">Welcome to the leading dating site for singles interested in serious dating. Search our women personals and browse through 1000s of profiles. If you're looking for long term relationships with beautiful women or handsome men, you've come to the right place. Start interacting with singles via chat - join free now! </p>
            <div class="row mt-5 icons">
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
        <div class="container recent-container text-center">
            <h3>Een greep uit onze leden</h3>
            <div class="row mt-5">
                @foreach ($users as $user)
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <a href="{{ route('users.show', ['userId' => $user->getId()])  }}"><img src="{{ \StorageHelper::profileImageUrl($user) }}" class="img-circle img-wide" alt=""></a>
                        <h5 class="small">{{ ucfirst($user->username) }}</h5>
                        <p class="offset-0">
                            <small class="text-muted">Age: {{ $user->meta->dob->diffInYears($carbonNow) }}, {{ $user->meta->city }}</small>
                        </p>
                        <a href="{{ route('users.show', ['userId' => $user->getId()])  }}" class="btn btn-lg btn-light">More Info</a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="container testimonials-container text-center">
            <h3>Success Stories</h3>
            <div id="carouselExampleIndicators" class="carousel slide mt-5" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <blockquote class="quote">
                            <div class="media-left">
                                <img class="img-wide" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_" alt="">
                            </div>
                            <div class="media-body">
                                <p class="q"><q>Thank you for helping me find my soul mate. You made the process of finding someone special very easy and fun. I will recommend this site to all my friends.
                                    </q></p>

                                <p class="cite"><cite class="text-muted">
                                        Ann &amp; Tom Black
                                    </cite></p>
                            </div>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="quote">
                            <div class="media-left">
                                <img class="img-wide" src="https://www.gannett-cdn.com/-mm-/c15f5f19a52dc82a8ae04998311685d79708bb7f/c=0-160-361-521/local/-/media/2017/12/21/INGroup/Indianapolis/636494825523802717-couple-1.png?width=200&height=200&fit=crop" alt="">
                            </div>
                            <div class="media-body">
                                <p class="q"><q>Thank you for helping me find my soul mate. You made the process of finding someone special very easy and fun. I will recommend this site to all my friends.
                                    </q></p>

                                <p class="cite"><cite class="text-muted">
                                        Kate &amp; John
                                    </cite></p>
                            </div>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="quote">
                            <div class="media-left">
                                <img class="img-wide" src="http://rs40.pbsrc.com/albums/e245/foxracingmotox/BeautifulThings10.jpg~c200" alt="">
                            </div>
                            <div class="media-body">
                                <p class="q"><q>Thank you for helping me find my soul mate. You made the process of finding someone special very easy and fun. I will recommend this site to all my friends.
                                    </q></p>

                                <p class="cite"><cite class="text-muted">
                                        Christine &amp; David
                                    </cite></p>
                            </div>
                        </blockquote>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
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
                    <img src="img/site_logos/Altijdsex_LogoBig_Neg.svg" />
                </div>
                <div class="col-md-12 copyright">
                    <h5>Copyright © 2019 altijdsex.nl</h5>
                </div>
            </div>
        </div>
    </footer>
</body>
