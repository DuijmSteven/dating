<div class="Tile User-summary">
    <div class="Tile__heading User-summary__heading">
        <a href="{{ route('users.show', ['userId' => $user->getId()])  }}">
            {{ $user->username }}{{ isset($user->meta->dob) ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}
        </a>
    </div>
    <div class="Tile__body User-summary__body">
        <div class="User-summary__user-image">
            <a href="{{ route('users.show', ['userId' => $user->getId()])  }}">
                <img src="{{ \StorageHelper::profileImageUrl($user) }}" alt="user image">
            </a>
        </div>
    </div>
</div>