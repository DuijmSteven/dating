<!DOCTYPE html>
<html lang="en">
@include('frontend.layouts.default.partials.head')

<body>
<div id="app">
    @include('frontend.layouts.default.partials.header')

    <div class="container" style="position: relative">
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

            @include('frontend.layouts.default.partials.search-bar')

        @endif

    </div>
</div>

<div class="ScrollTopButton JS--ScrollTopButton hidden">
    <i class="material-icons">
        arrow_upward
    </i>
</div>

<!-- Creates the bootstrap modal where the image will appear -->
@include('frontend.components.image-modal')

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
        usersCloudPath: '{{ Storage::disk('cloud')->url('users') }}',
        malePlaceholderImageUrl: '{{ url('/') . '/img/' . 'avatars/male.jpg' }}',
        femalePlaceholderImageUrl: '{{ url('/') . '/img/' . 'avatars/female.jpg' }}',
        creditsUrl: '{{ route('credits.show') }}',
        singleProfileUrl: '{{ url('/') . '/users/' }}',
    };
</script>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>