<!DOCTYPE html>
<html lang="en">

@include('admin.layouts.default.partials.head')

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include('admin.layouts.default.partials.header')
        @include('admin.layouts.default.partials.main-sidebar')
        @include('admin.layouts.default.partials.main')
        @include('admin.layouts.default.partials.footer')
        @include('admin.layouts.default.partials.control-sidebar')
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
            currentRoute: '{!! request()->route()->getName() !!}'
        };
    </script>

    @include('admin.layouts.default.partials.scripts')
</body>
</html>