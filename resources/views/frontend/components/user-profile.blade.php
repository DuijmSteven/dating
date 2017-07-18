<div class="Tile User-profile">
    <div class="Tile__heading User-profile__heading">
        {{ $user->username }}, {{ $user->meta->dob->diffInYears($carbonNow)  }}
    </div>
    <div class="Tile__body User-profile__body">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <div class="User-profile__user-image">
                    <img src="{{ \StorageHelper::profileImageUrl($user) }}" alt="user image">
                </div>
                <div class="row">
                    @php
                        $galleryImages = $user->nonProfileImages;
                    @endphp
                    @foreach($galleryImages as $galleryImage)
                        <div class="col-xs-6">
                            <div class="User-profile__user-image">
                                <img src="{{ \StorageHelper::userImageUrl($galleryImage->user_id, $galleryImage->filename) }}" alt="user image">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <h5>About me</h5>
                <hr>
                <div class="User-profile__text">
                    {{ $user->meta->about_me }}
                </div>
            </div>
        </div>
    </div>
</div>