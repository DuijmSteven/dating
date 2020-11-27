<nav class="Navbar">
    @if(isset($authenticatedUser) && $authenticatedUser->isAdmin())
        <div id="goToAdmin">
            <a href="{!! route('admin.dashboard') !!}">
                Admin
            </a>
        </div>
    @endif

    <a class="Navbar__brand" href="{!! route('home') !!}">
        <img class="Navbar__brand__logo"
             src="{!! asset('img/site_logos/' . config('app.directory_name') . '/main_logo_negative.png') !!}"
             alt="Logo"
        >
    </a>

    <div class="Navbar__centralMenu">
        @if(isset($authenticatedUser))
            <a
                href="{{ route('home') }}"
                class="Navbar__centralMenu__item {!! \Request::route()->getName() == 'home' ? 'active' : '' !!}"
                title="{{ trans(config('app.directory_name') . '/navbar.newsfeed') }}"
            >
                <span class="material-icons Navbar__centralMenu__item__icon">
                    home
                </span>
            </a>

            <div
                class="Navbar__centralMenu__item JS--searchToggleButton"
                title="{{ trans(config('app.directory_name') . '/search.search') }}"
            >
                <span class="material-icons Navbar__centralMenu__item__icon">
                    search
                </span>
            </div>

            <a
                href="{!! route('users.edit-profile.get', ['username' => $authenticatedUser->getUsername()]) !!}"
                class="Navbar__centralMenu__item {!! \Request::route()->getName() == 'users.edit-profile.get' ? 'active' : '' !!}"
                title="{{ trans(config('app.directory_name') . '/navbar.edit_profile') }}"
            >
                <span class="material-icons Navbar__centralMenu__item__icon">
                    build
                </span>
            </a>

            <a
                href="{{ route('credits.show') }}"
                class="Navbar__centralMenu__item {!! \Request::route()->getName() == 'credits.show' ? 'active' : '' !!}"
                title="{{ trans(config('app.directory_name') . '/navbar.credits') }}"
            >
                <span style="position: relative; display: flex">
                    <span class="material-icons Navbar__centralMenu__item__icon">
                        payments
                    </span>
                    <credits-count
                        v-if="userCredits >= 0"
                        :credits="userCredits"
                        :template="'number'"
                    >
                    </credits-count>
                </span>

            </a>

            <a
                href="{{ route('logout.post') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
                class="Navbar__centralMenu__item"
                title="{{ trans(config('app.directory_name') . '/navbar.logout') }}"
            >
                <form id="logout-form" action="{!!  route('logout.post') !!}" method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>

                <span class="material-icons Navbar__centralMenu__item__icon">
                    logout
                </span>
            </a>
        @else
            <a
                href="{{ route('ads-lp.show', ['id' => 1, 'lpType' => 'login']) }}"
                class="Navbar__centralMenu__item loggedOut {!! \Request::route()->getName() == 'home' ? 'active' : '' !!}"
                title="{{ trans(config('app.directory_name') . '/navbar.login') }}"
            >
                <span class="material-icons Navbar__centralMenu__item__icon">
                    login
                </span>
                {{ trans(config('app.directory_name') . '/navbar.login') }}
            </a>
            <a
                href="{{ route('ads-lp.show', ['id' => 1, 'lpType' => 'register']) }}"
                class="Navbar__centralMenu__item loggedOut {!! \Request::route()->getName() == 'home' ? 'active' : '' !!}"
                title="{{ trans(config('app.directory_name') . '/navbar.register') }}"
            >
                <span class="material-icons Navbar__centralMenu__item__icon">
                    create
                </span>
                {{ trans(config('app.directory_name') . '/navbar.register') }}
            </a>
        @endif
    </div>

    @if(isset($authenticatedUser))

        <div class="Navbar__rightMenu">
            <img
                class="Navbar__rightMenu__profileImage"
                src="{{ \StorageHelper::profileImageUrl($authenticatedUser, true) }}" alt=""
            >
            <span class="Navbar__rightMenu__username">{{ $authenticatedUser->username }}</span>
    {{--        <span class="material-icons Navbar__centralMenu__item__downIcon">--}}
    {{--            expand_more--}}
    {{--        </span>--}}
        </div>

    @endif
</nav>
