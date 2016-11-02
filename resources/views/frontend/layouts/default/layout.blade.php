<!doctype html>
<html lang="en">
    @include('frontend.layouts.default.partials.head')

<body>
    <div class="container">
        @include('frontend.layouts.default.partials.header')

        @if(isset($hasSidebar) && $hasSidebar)
            <div class="row">
                <div class="col-md-9">
                    @include('frontend.layouts.default.partials.main')
                </div>
                <div class="col-md-3 hidden-xs hidden-sm">
                    @include('frontend.layouts.default.partials.sidebar')
                </div>
            </div>
        @else
            @include('frontend.layouts.default.partials.main')
        @endif
        
        @include('frontend.layouts.default.partials.footer')
    </div>

    <script>
        /*
         * Application namespace
         *
         * Data that needs to be passed from the PHP application to JS
         * can be defined here
         */
        var DP = {
            baseUrl: '{!! url() !!}'
        };
    </script>
    <script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>