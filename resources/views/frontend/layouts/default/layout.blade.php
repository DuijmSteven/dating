<!DOCTYPE html>
<html lang="en">
@include('frontend.layouts.default.partials.head')

<body>
@if(App::environment('production'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJG2S4N"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
<div id="app">
    @include('frontend.layouts.default.partials.header')

    <div class="container" style="position: relative; padding-top: 80px">
        @if(
            isset($authenticatedUser) &&
            ($authenticatedUser->profileRatioFilled < 0.3 || !$authenticatedUser->profileImage) &&
            !$authenticatedUser->hasRecentlyAcceptedProfileCompletionMessage
        )
            @include('frontend.components.low-profile-completion', ['user' => $authenticatedUser])
        @endif

        @if(isset($sidebarCount) && $sidebarCount > 0)
            <div class="row">
                @if($leftSidebar)
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        @include('frontend.layouts.default.partials.left-sidebar')
                    </div>
                @endif

                @if($rightSidebar)
                    <div class="col-xs-12 col-sm-12 col-md-3 col-md-push-6">
                        @include('frontend.layouts.default.partials.right-sidebar')
                    </div>
                @endif

                <div class="col-xs-12 col-sm-12 col-md-{!! $sidebarCount == 1 ? '9' : '6' !!} col-md-pull-3">
                    @include('frontend.layouts.default.partials.main')
                </div>
            </div>
        @else
            @include('frontend.layouts.default.partials.main')
        @endif

        @if(isset($authenticatedUser) && $authenticatedUser != null && \Request::route()->getName() !== 'payments.check')
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

@if(isset($authenticatedUser) && !$authenticatedUser->hasMilestone(\App\Milestone::ACCEPTED_WELCOME_MESSAGE))
    @include('frontend.layouts.default.partials.welcome-modal')
@endif

<div class="ScrollTopButton JS--ScrollTopButton hidden">
    <i class="material-icons">
        arrow_upward
    </i>
</div>

<!-- Creates the bootstrap modal where the image will appear -->
@include('frontend.components.image-modal')

@if(
    !isset($isAnonymousDomain) ||
    !$isAnonymousDomain
)
    @include('frontend.layouts.default.partials.footer')
@endif

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
        locale: '{{ app()->getLocale() }}'
    };
</script>
<script src="{{ mix('js/app.js') }}"></script>

@toastr_js
@toastr_render
</body>
</html>
