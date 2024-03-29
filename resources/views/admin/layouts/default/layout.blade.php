
<!DOCTYPE html>
<html>
<head>
    @include('admin.layouts.default.partials.head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        @if(
            isset($authenticatedUser) &&
            !($authenticatedUser->isOperator() || $authenticatedUser->isEditor())
        )
            <a href="{!! route('admin.dashboard') !!}" class="logo">
                @if(config('app.pure_name') === 'operator-platform')
                    Devely
                @else
                    <img class="site-logo" src="{!! asset('img/site_logos/' . config('app.directory_name') . '/main_logo.png') !!}">
                @endif
            </a>
        @endif

    <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        @include('admin.layouts.default.partials.main-sidebar')
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('admin.layouts.default.partials.alerts')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            @include('admin.layouts.default.partials.content-header')
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        @include('admin.layouts.default.partials.footer')
    </footer>

    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
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
        currentRoute: '{!! request()->route()->getName() !!}',
        operatorDashboardRoute: '{!! route('operator-platform.dashboard') !!}',
        authenticatedUserIsAdmin: '{{ $authenticatedUser->isAdmin() }}',
        authenticatedUserRole: '{{ $authenticatedUser->roles[0]->name }}',
        authenticatedUserID: '{{ $authenticatedUser->getId() }}',
        authenticatedUserApiToken: '{{ $authenticatedUser->getApiToken() }}',
    };
</script>

<!-- ./wrapper -->
<script src="{{ mix('admin/js/custom-force.js') }}"></script>
<script src="{{ mix('admin/js/plugins-force.js') }}"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
{{--<script>--}}
{{--  $.widget.bridge('uibutton', $.ui.button);--}}
{{--</script>--}}

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</body>
</html>
