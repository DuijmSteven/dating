<div class="Tile User-account">
    <div class="Tile__heading User-account__heading">
        <div class="User-account__profile-image">
            <img src="{{ \StorageHelper::profileImageUrl($authenticatedUser, true) }}" alt="">
        </div>
        <div class="User-account__username">
            {{ $authenticatedUser->username }}
        </div>
    </div>
    <div class="Tile__body User-account__body">
        <div class="User-account__item">
            <div class="User-account__item__icon">
                <i class="fa fa-user"></i>
            </div>
            <div class="User-account__item__text">
                Edit profile and picture
            </div>
        </div>
        <div class="User-account__item">
            <div class="User-account__item__icon">
                <i class="fa fa-money"></i>
            </div>
            <div class="User-account__item__text">
                Credits
            </div>
        </div>
    </div>
</div>