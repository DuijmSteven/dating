<div class="Tile LatestViewed ModuleProfiles JS--Tile Tile--withToggle">
    <div class="Tile__heading LatestViewed__heading JS--Tile__toggle">
        <span class="material-icons">
            remove_red_eye
        </span>

        {{ trans(config('app.directory_name') . '/viewed_users.viewed_users') }}

        @include('frontend.components.tile-toggle')
    </div>
    <div class="Tile__body LatestViewed__body ModuleProfiles__body JS--Tile__body">
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
                {{ trans(config('app.directory_name') . '/viewed_by_users.no_views') }}
            </p>
        @endforelse
    </div>
</div>