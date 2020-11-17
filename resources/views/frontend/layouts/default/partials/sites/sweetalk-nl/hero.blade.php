<div class="Hero">
    <div class="Hero__profileImageContainer">
        <img
            class="Hero__profileImage"
            src="{{ $authenticatedUser->profileImageUrl }}"
            alt=""
        >
    </div>
    <div class="Hero__textContainer">
        Hallo {{ $authenticatedUser->getUsername() }}
    </div>

    <div class="Hero__buttonsContainer">
        Buttons
    </div>
</div>
