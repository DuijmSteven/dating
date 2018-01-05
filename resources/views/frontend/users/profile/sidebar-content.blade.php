<div class="Tile User-profile__sidebar">
    <div class="Tile__heading User-profile__heading">
        {{ $user->username }}, {{ $user->meta->dob->diffInYears($carbonNow)  }}
    </div>
    <div class="Tile__body User-profile__sidebar__body">
        <div class="User-profile__user-profile-image">
            <img src="{{ \StorageHelper::profileImageUrl($user) }}" alt="user profile image">
        </div>
        <div class="row">
            @php
                $galleryImages = $user->nonProfileImages;
            @endphp
            @foreach($galleryImages as $galleryImage)
                <div class="col-xs-6">
                    <div class="User-profile__user-image">
                        <img src="{{ \StorageHelper::userImageUrl($galleryImage->user_id, $galleryImage->filename) }}"
                             alt="user image">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>