<div class="Tile Online-users">
    <div class="Tile__heading Online-users__heading">
        <span class="onlineCircle blinking"></span>

        {{ trans('online_users.online_users') }}
    </div>
    <div class="Tile__body Online-users__body">
        @forelse($onlineUsers as $user)
            <div class="Online-users__bodyWrapper">
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
                </div >
            </div>
        @empty
            <p>
                {{ trans('online_users.no_online_users') }}
            </p>
        @endforelse
    </div>
</div>