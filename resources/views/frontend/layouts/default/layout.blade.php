<!doctype html>
<html lang="en">
    @include('frontend.layouts.default.partials.head')

<body>
    @include('frontend.layouts.default.partials.header')

    <div class="container">
        @include('frontend.layouts.default.partials.alerts')

        @if(isset($sidebarCount) && $sidebarCount > 0)
            <div class="row">
                @if($leftSidebar)
                    <div class="col-md-3 hidden-xs hidden-sm">
                        @include('frontend.layouts.default.partials.left-sidebar')
                    </div>
                @endif

                <div class="col-md-{!! $sidebarCount == 1 ? '9' : '6' !!}">
                    @include('frontend.layouts.default.partials.main')
                </div>

                @if($rightSidebar)
                    <div class="col-md-3 hidden-xs hidden-sm">
                        @include('frontend.layouts.default.partials.right-sidebar')
                    </div>
                @endif
            </div>
        @else
            @include('frontend.layouts.default.partials.main')
        @endif
    </div>

    @include('frontend.layouts.default.partials.footer')

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
    <script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>