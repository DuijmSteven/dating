<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!! route('home') !!}">Dating</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if(isset($authenticatedUser))
                <ul class="nav navbar-nav">
                    <li class="{!! \Request::route()->getName() == 'users.retrieve' ? 'active' : '' !!}">
                        <a href="{{ route('users.retrieve') }}">
                            {{ @trans('profiles.main_heading') }}
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="{!! str_contains(\Request::route()->getName(), 'users.search') ? 'active' : '' !!}"><a href="{{ route('users.search.get') }}">Search for users</a>
                    </li>
                    <li class="{!! \Request::route()->getName() == 'users.online' ? 'active' : '' !!}"><a href="{{ route('users.online') }}">Online users</a>
                    </li>
                </ul>
            @endif

            <ul class="nav navbar-nav navbar-right">
                @if(isset($authenticatedUser))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ $authenticatedUser['username'] }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @if($authenticatedUser->isAdmin())
                                <li>
                                    <a href="{!! route('backend.dashboard') !!}">
                                        Administration
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{!!  route('logout.post') !!}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{!!  route('logout.post') !!}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{!! route('register.get') !!}">
                            Register
                        </a>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>