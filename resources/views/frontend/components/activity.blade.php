<div class="Tile Activity">
    <div class="Tile__heading Activity__heading">
        <div class="Activity__profile-image">
            <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                <img src="{{ $activityThumbnailUrl }}" alt="">
            </a>
        </div>
        <div class="Activity__user-details">
            <div class="Activity__title">
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                    {{ $activityTitle }}{{ $user->meta->dob ? ', ' : '' }}
                    {{ $user->meta->dob ? $user->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                </a>

                @if(!in_array($user->getId(), $onlineUserIds))
                    <span class="onlineCircle"></span>
                @endif
            </div>

    {{--            @if(isset($activityDate))--}}
    {{--                <div class="Activity__date">{{ $activityDate }}</div>--}}
    {{--            @endif--}}
        </div>
    </div>
    <div class="Tile__body Activity__body">
        @if(isset($activityImageUrl))
            <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                <img src="{{ $activityImageUrl }}" alt="">
            </a>
        @endif
        @if(isset($activityText))
            <div class="Activity__text">
                {{ $activityText }}
            </div>
        @endif
    </div>
</div>