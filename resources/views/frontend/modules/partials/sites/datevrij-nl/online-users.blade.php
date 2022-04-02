<div class="Tile Online-users ModuleProfiles JS--Tile Tile--withToggle">
    <div class="Tile__heading Online-users__heading JS--Tile__toggle">
        <span class="onlineCircle blinking"></span>

        <span class="Online-users__headingText">{{ trans(config('app.directory_name') . '/online_users.online_users') }}</span>

        {{--        @include('frontend.components.tile-toggle')--}}

        <div class="Tile__toggle JS--Tile__toggleExpand hidden">
            <span class="material-icons">
                expand_more
            </span>
        </div>

        <div class="Tile__toggle JS--Tile__toggleCollapse">
            <span class="material-icons">
                expand_less
            </span>
        </div>
    </div>
    <div class="Tile__body Online-users__body ModuleProfiles__body JS--Tile__body">
        @forelse($users as $user)
            <div class="ModuleProfiles__bodyWrapper">
                <a href="{!! route('users.show', ['username' => $user->getUsername()]) !!}" class="ModuleProfiles__item">
            <span class="ModuleProfiles__profile-image">
                <img src="{!! $user->profileImageUrlThumb !!}" alt="">

                @if(in_array($user->getId(), $onlineUserIds))
                    <div class="onlineCircle"></div>
                @endif
            </span>
                    <span class="ModuleProfiles__username">
                {{ $user->username }}{{ $user->meta->dob ? ', ' : '' }}
                        {{ $user->meta->dob ? $user->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
            </span>
                </a>

                <div class="ModuleProfiles__sendMessage"
                     v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
                >
                    <i class="material-icons material-icon ModuleProfiles__sendMessage__icon">textsms</i>
                </div>
            </div>
        @empty
            <p>
                {{ trans(config('app.directory_name') . '/online_users.no_online_users') }}
            </p>
        @endforelse
    </div>
</div>