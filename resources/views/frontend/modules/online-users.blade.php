<div class="Tile Online-users">
    <div class="Tile__heading Online-users__heading">
        Online users
    </div>
    <div class="Tile__body Online-users__body">
        @foreach($onlineUsers as $user)
            <div class="Online-users__item">
                <div class="Online-users__profile-image">
                    <a href="{!! route('users.show', ['userId' => $user->id]) !!}">
                        <img src="{!! \StorageHelper::profileImageUrl($user, true) !!}" alt="">
                    </a>
                </div>
                <div class="Online-users__username">
                    <a href="{!! route('users.show', ['userId']) !!}">
                        {{ $user->username }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>