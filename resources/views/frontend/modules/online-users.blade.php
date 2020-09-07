<div class="Tile Online-users JS--Tile Tile--withToggle">
    <div class="Tile__heading Online-users__heading JS--Tile__toggle">
        <span class="onlineCircle blinking"></span>

        {{ trans('online_users.online_users') }}

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
    <div class="Tile__body Online-users__body JS--Tile__body">
        @forelse($onlineUsers as $user)
            <div class="Online-users__bodyWrapper {{ $loop->index > 7 ? 'hidden-xs' : '' }}">
                <a href="{!! route('users.show', ['username' => $user->getUsername()]) !!}" class="Online-users__item">
                    <span class="Online-users__profile-image">
                        <img src="{!! \StorageHelper::profileImageUrl($user, true) !!}" alt="">
                    </span>

                    <span class="Online-users__username">
                        {{ $user->username }}{{ $user->meta->dob ? ', ' : '' }}
                        {{ $user->meta->dob ? $user->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                    </span>
                </a>

                <div class="Online-users__sendMessage"
                     v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
                >
                    <i class="material-icons material-icon Online-users__sendMessage__icon">textsms</i>
                </div>
            </div>
        @empty
            <p>
                {{ trans('online_users.no_online_users') }}
            </p>
        @endforelse
    </div>
</div>