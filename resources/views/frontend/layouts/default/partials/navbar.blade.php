<nav class="navbar navbar-default">

    @if(isset($authenticatedUser) && $authenticatedUser->isAdmin())
        <div id="goToAdmin">
            <a href="{!! route('admin.dashboard') !!}">
                Admin
            </a>
        </div>
    @endif

    <div class="container">
        @if(isset($city) && isset($radius))
            <h3 class="searchResultsHeader JS--searchResultsHeader">{!! trans('user_search.search_results_heading', ['city' => $city]) !!}</h3>
        @endif

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            @if(
                !isset($isAnonymousDomain) ||
                !$isAnonymousDomain
            )
                <a class="navbar-brand" href="{!! route('home') !!}">
                    <img src="{!! asset('img/site_logos/Altijdsex_LogoSmall_Pos@1x.png') !!}"
                         alt="Logo"
                    >
                </a>
            @endif

            @if(isset($authenticatedUser))
                <a class="buyCreditsButton" href="{{ route('credits.show') }}">
                    {{ trans('navbar.credits') }}
                </a>

                <div class="searchToggleButton JS--searchToggleButton">
                    <span class="searchToggleButtonText">{{ @trans('search.search') }}</span>
                    <i class="material-icons">
                        search
                    </i>
                </div>

                <div class="userCredits__smallScreens">
                    <a href="{{ route('credits.show') }}">
                        <credits-count></credits-count>
                    </a>
                </div>
            @endif
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if (isset($authenticatedUser))
                    <li class="userCredits">
                        <div>
                            <a href="{{ route('credits.show') }}">
                                <credits-count></credits-count>
                            </a>
                        </div>

                        <div class="vertical-separator"></div>
                    </li>
                    <li class="dropdown userDropdown">
                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-haspopup="true"
                           aria-expanded="false"
                        >
                            <img
                                class="userDropdown__image"
                                src="{{ \StorageHelper::profileImageUrl($authenticatedUser, true) }}" alt=""
                            >

                            <span class="userDropdown__username">{{ $authenticatedUser->username }}</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{!! route('users.edit-profile.get', ['userId' => $authenticatedUser->getUsername()]) !!}">{{ @trans('navbar.edit_profile') }}</a></li>
                            <li><a href="{{ route('credits.show') }}">{{ @trans('navbar.credits') }}</a></li>

                            <li class="dropdown-submenu languagesSubmenu">
                                <a class="JS--showLanguagesSubmenu" tabindex="-1" href="#">{{ @trans('navbar.language') }} <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="languagesSubmenu__item" href="{{ route('users.set-locale', ['locale' => 'en']) }}">
                                            <div class="flagImageWrapper">
                                                <img class="flagImage" src="{{ asset('img/flags/uk.svg') }}" alt="">
                                            </div>
                                            <span>{{ @trans('navbar.english') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="languagesSubmenu__item" href="{{ route('users.set-locale', ['locale' => 'nl']) }}">
                                            <div class="flagImageWrapper">
                                                <img class="flagImage" src="{{ asset('img/flags/nl.svg') }}" alt="">
                                            </div>
                                            <span>{{ @trans('navbar.dutch') }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="showSelectedLanguage">
                                    <div class="flagImageWrapper">
                                        @if($authenticatedUser->getLocale() === 'en')
                                            <img class="flagImage" src="{{ asset('img/flags/uk.svg') }}" alt="">
                                        @elseif($authenticatedUser->getLocale() === 'nl')
                                            <img class="flagImage" src="{{ asset('img/flags/nl.svg') }}" alt="">
                                        @endif
                                    </div>
                                </div>
                            </li>

                            <li>
                                <a href="{!!  route('logout.post') !!}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ @trans('navbar.logout') }}
                                </a>

                                <form id="logout-form" action="{!!  route('logout.post') !!}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>

                        </ul>

                        <div class="vertical-separator"></div>
                    </li>

                    <li class="{!! str_contains(\Request::route()->getName(), 'home') ? 'active' : '' !!}"><a
                            href="{{ route('home') }}">{{ @trans('navbar.home') }}</a>
                    </li>

                @else
                    @if(
                        !isset($isAnonymousDomain) ||
                        !$isAnonymousDomain
                    )

                        <li class="{!! \Request::route()->getName() == 'login.get' ? 'active' : '' !!}">
                            <a href="{{ route('landing-page.show-login') }}">{{ @trans('navbar.login') }}</a>
                        </li>
                        <li class="{!! \Request::route()->getName() == 'landing-page.show' ? 'active' : '' !!}">
                            <a href="{{ route('landing-page.show-register') }}">{{ @trans('navbar.register') }}</a>
                        </li>
                    @endif
                @endif

            </ul>
        </div>

    </div>
</nav>
