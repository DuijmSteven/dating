<!doctype html>
<html lang="en">
    @include('frontend.layouts.default.partials.head')

<body>
    @include('frontend.layouts.default.partials.header')

    <div class="container"
         id="app"
    >
        {{--@include('frontend.components.alert')--}}

        @include('toast::messages')

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

        @if(isset($authenticatedUser) && $authenticatedUser != null)
            <private-chat
                v-model="conversationPartners"
                v-for="(partner, index) in conversationPartners"
                :index="index"
                :key="partner.id"
                :user="{{ $authenticatedUser }}"
                :partner="partner"
            >
            </private-chat>

            <private-chat-manager
                ref="privateChatManager"
                :user="{{ $authenticatedUser }}"
            >
            </private-chat-manager>
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
            baseUrl: '{!! url('/') !!}',
            authenticatedUser: <?php echo json_encode($authenticatedUser, true) ?>,
            conversationsCloudUrl: '{{ Storage::disk('cloud')->url('conversations') }}',
        };
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>