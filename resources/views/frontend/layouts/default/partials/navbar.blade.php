<nav class="navbar navbar-default">

    @if(isset($authenticatedUser) && $authenticatedUser->isAdmin())
        <div id="goToAdmin">
            <a href="{!! route('admin.dashboard') !!}">
                Admin
            </a>
        </div>
    @endif

    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!! route('home') !!}">
                <img src="{!! asset('img/site_logos/Altijdsex_LogoSmall_Pos@1x.png') !!}">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if(isset($authenticatedUser))
                    <li class="userCredits">
                        <div class="userCredits">
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
                            <div class="userDropdown__imageContainer">
                                <img
                                    class="userDropdown__image"
                                    src="{{ \StorageHelper::profileImageUrl($authenticatedUser, true) }}" alt=""
                                >
                            </div>

                            {{ $authenticatedUser->username }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{!! route('edit-profile.get') !!}">Edit profile</a></li>
                            <li><a href="{{ route('credits.show') }}">Credits</a></li>

                            <li>
                                <a href="{!!  route('logout.post') !!}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Logout
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
                            href="{{ route('home') }}"><i class="fa fa-fw fa-newspaper-o"></i>Home</a>
                    </li>
                @else
                    <li class="{!! \Request::route()->getName() == 'login.get' ? 'active' : '' !!}">
                        <a href="{{ route('landing-page.show') }}">Login</a>
                    </li>
                    <li class="{!! \Request::route()->getName() == 'landing-page.show' ? 'active' : '' !!}">
                        <a href="{{ route('landing-page.show') }}">Register</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>