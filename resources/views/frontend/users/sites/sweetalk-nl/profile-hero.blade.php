<div class="Hero Hero--userProfile">
    <div class="Hero__profileImageContainer">
        <img
            class="Hero__profileImage"
            src="{{ $user->profileImageUrl }}"
            alt=""
        >
    </div>
    <div class="Hero__textContainer">
        {{ $user->getUsername() }}{{ $user->meta->dob ? ', ' . $user->meta->dob->diffInYears($carbonNow) . ' Jaar' : '' }}

        <div
            class="Hero__chatButtonContainer"
            v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
        >
            <div
                class="Hero__chatButton Hero__chatButton--bordered"
            >
                <span class="material-icons">
                    chat_bubble
                </span>
            </div>

            <div class="Hero__chatButton__text">
                {{ trans(config('app.directory_name') . '/user_profile.chat')  }}
            </div>
        </div>
    </div>

    <div class="Hero__buttonsOuterContainer">
        @if(in_array($user->getId(), $onlineUserIds))
            <div class="Hero__buttonContainer">
                <div
                    class="Hero__button"
                >
                    <span class="material-icons online blinking">
                        stop_circle
                    </span>
                </div>

                <div class="Hero__button__text">
                    {{ trans(config('app.directory_name') . '/user_profile.online')  }}
                </div>
            </div>
        @endif

        <div class="Hero__buttonContainer">
            <div
                class="Hero__button"
            >
                <span class="material-icons">
                    location_on
                </span>
            </div>

            <div class="Hero__button__text">
                {{ $user->meta->getCity() ? $user->meta->getCity() : trans(config('app.directory_name') . '/user_profile.unknown')  }}
            </div>
        </div>
    </div>
</div>
