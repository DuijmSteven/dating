<div class="Tile UserSummary" data-user-id="{!! $user->getId() !!}">
{{--    <div class="Tile__heading UserSummary__heading">
        <a href="{{ route('users.show', ['userId' => $user->getId()])  }}">
            {{ $user->username }}{{ isset($user->meta->dob) ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}
        </a>
    </div>--}}
    <div class="Tile__body UserSummary__body JS--UserSummary">
        <div class="UserSummary__user-image JS--UserSummary__user-image">
            @if($user->hasProfileImage())
                <a href="#" class="modalImage">
                    <div class="UserSummary__profileImageWrapper">
                        <img
                            class="UserSummary__profileImage"
                            src="{{ \StorageHelper::profileImageUrl($user) }}"
                            alt="user image"
                        >
                    </div>
                </a>
            @else
                <img
                    src="{{ \StorageHelper::profileImageUrl($user) }}"
                    alt="user image"
                >
            @endif
        </div>
    </div>
    <div class="Tile__footer UserSummary__footer">
        <div class="UserSummary__userInfo">
            <a href="{{ route('users.show', ['userId' => $user->getId()])  }}"
               class="UserSummary__userInfo__primary">
                {{ $user->username }}
            </a>
            <div class="UserSummary__userInfo__additional">
                {!! isset($user->meta->city) ? $user->meta->city . " <span>&centerdot;</span>" : '' !!}
                {{ $user->meta->dob->diffInYears($carbonNow) }} Jaar
            </div>
        </div>
        <div class="UserSummary__sendMessage"
             v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)">
            <i class="material-icons material-icon UserSummary__sendMessage__icon">textsms</i>
        </div>
    </div>
</div>