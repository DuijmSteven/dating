<div class="Tile User-summary">
    <div class="Tile__heading User-summary__heading">
        {{ $user->username }}{{ isset($user->meta->dob) ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}
    </div>
    <div class="Tile__body User-summary__body">
        <div class="User-summary__user-image">
            <img src="{{ \StorageHelper::profileImageUrl($user) }}" alt="user image">
        </div>
    </div>
</div>