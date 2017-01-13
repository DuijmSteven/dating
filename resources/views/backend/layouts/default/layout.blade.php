<!DOCTYPE html>
<html lang="en">

@include('backend.layouts.default.partials.head')

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include('backend.layouts.default.partials.header')
        @include('backend.layouts.default.partials.main-sidebar')
        @include('backend.layouts.default.partials.main')
        @include('backend.layouts.default.partials.footer')
        @include('backend.layouts.default.partials.control-sidebar')
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

    @include('backend.layouts.default.partials.scripts')
</body>
</html>