<div class="Tile Online-users">
    <div class="Tile__heading Online-users__heading">
        Online users
    </div>
    <div class="Tile__body Online-users__body">
        @foreach($onlineUsers as $user)
            <div class="Online-users__item">
                <div class="Online-users__profile-image">
                    <img src="http://lorempixel.com/100/100/people" alt="">
                </div>
                <div class="Online-users__username">
                    {{ $user->username }}
                </div>
            </div>
        @endforeach
    </div>
</div>