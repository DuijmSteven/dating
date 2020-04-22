<div class="Tile Online-users">
    <div class="Tile__heading Online-users__heading">
        {{ trans('online_users.online_users') }} <span class="onlineCircle blinking"></span>
    </div>
    <div class="Tile__body Online-users__body">
        @forelse($onlineUsers as $user)
            <a href="{!! route('users.show', ['username' => $user->getUsername()]) !!}" class="Online-users__item">
                <div class="Online-users__profile-image">
                    <img src="{!! \StorageHelper::profileImageUrl($user, true) !!}" alt="">
                </div>
                <div class="Online-users__username">
                    {{ $user->username }}{{ $user->meta->dob ? ', ' : '' }}
                    {{ $user->meta->dob ? $user->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                </div>
            </a>
        @empty
            <p>
                {{ trans('online_users.no_online_users') }}
            </p>
        @endforelse
    </div>
</div>