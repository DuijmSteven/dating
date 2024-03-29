<!DOCTYPE html>
<html lang="en">
@include('frontend.layouts.default.partials.head')

<body>
@if(config('app.env') === 'production')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJG2S4N"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
<div id="app" class="fullWrapper {!! \Request::route()->getName() == 'password.reset.get' ? 'withBackground' : '' !!}">
    @include('frontend.layouts.default.partials.header')

    @if(
        (int) config('app.site_id') === \App\Helpers\SiteHelper::SWEETALK_NL &&
        Route::currentRouteNamed('home')
    )
        @include('frontend.layouts.default.partials.sites.sweetalk-nl.homepage-hero')
    @elseif(
        (int) config('app.site_id') === \App\Helpers\SiteHelper::SWEETALK_NL &&
        Route::currentRouteNamed('users.show')
    )
        @include('frontend.users.sites.sweetalk-nl.profile-hero')
    @endif

    <div class="container mainContainer {!! \Request::route()->getName() == 'payments.check' ? ' mainContainer--reducedTopPadding-md-up' : '' !!}">
        @if(
            isset($authenticatedUser) &&
            $authenticatedUser != null &&
            \Request::route()->getName() !== 'payments.check'
        )
            @include('frontend.layouts.default.partials.sites.' . config('app.directory_name') . '.search-bar')
        @endif

        @if($authenticatedUser && $authenticatedUser->getDiscountPercentage())
            <div class="row">
                <div class="col-xs-12">
                    <a href="{{ route('credits.show') }}" class="DiscountHeader">
                        Tijdelijk <span class="DiscountHeader__stress"> {{ $authenticatedUser->getDiscountPercentage() }}% korting </span> op je volgende aankoop, {{ ucfirst($authenticatedUser->getUsername()) }}!
                    </a>
                </div>
            </div>
        @endif

{{--        @if(--}}
{{--            isset($authenticatedUser) &&--}}
{{--            ($authenticatedUser->profileRatioFilled < 0.3 || !$authenticatedUser->profileImage) &&--}}
{{--            !$authenticatedUser->hasRecentlyAcceptedProfileCompletionMessage &&--}}
{{--            \Carbon\Carbon::now('Europe/Amsterdam')->gt($authenticatedUser->getCreatedAt()->tz('Europe/Amsterdam')->addMinutes(5))--}}
{{--        )--}}
{{--            @include('frontend.components.low-profile-completion', ['user' => $authenticatedUser])--}}
{{--        @endif--}}

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

                <div
                    class="col-xs-12 col-sm-12 col-md-{{ $sidebarCount == 1 ? '9' : '6' }} col-md-pull-3 {{ $sidebarCount === 2 ? 'removePadding' : ''}}"
                >
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
                v-if="conversationManagerDataFullyLoaded"
                ref="privateChatManager"
                :user="{{ $authenticatedUser }}"
                :conversations="conversations"
                :newMessagesCount="countConversationsWithNewMessages"
                :fetchingUserConversations="fetchingUserConversations"
                :maximized="managerMaximized"
            >
            </private-chat-manager>
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
    @include('frontend.layouts.default.partials.sites.' . config('app.directory_name') .  '.footer')
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
        malePlaceholderImageUrl: '{{ url('/') . '/' . \App\Helpers\StorageHelper::$genderAvatarsDir . 'sites/' . config('app.directory_name') . '/male.jpg' }}',
        femalePlaceholderImageUrl: '{{ url('/') . '/' . \App\Helpers\StorageHelper::$genderAvatarsDir . 'sites/' . config('app.directory_name') . '/female.jpg' }}',
        creditsUrl: '{{ route('credits.show') }}',
        singleProfileUrl: '{{ url('/') . '/users/' }}',
        locale: '{{ app()->getLocale() }}',
        postChatItemRoute: '{{ route('public-chat-items.post') }}',
        csrfToken: '{{ csrf_token() }}',
        publicChatItemPeasantType: '{{ \App\PublicChatItem::TYPE_PEASANT }}',
        appDirectoryName: '{{ config('app.directory_name') }}',
        sanctumToken: '{{ \Illuminate\Support\Facades\Cache::get('sanctum_token') }}'
    };
</script>

<script src="{{ mix('js/' . config('app.directory_name') . '/app.js') }}"></script>

@toastr_js
@toastr_render
</body>
</html>
