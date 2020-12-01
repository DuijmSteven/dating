<div class="Hero">
    <div class="Hero__profileImageContainer">
        <img
            class="Hero__profileImage"
            src="{{ $authenticatedUser->profileImageUrl }}"
            alt=""
        >
    </div>
    <div class="Hero__textContainer">
        @if(\Carbon\Carbon::now('Europe/Amsterdam')->hour >= 2 && \Carbon\Carbon::now('Europe/Amsterdam')->hour < 12)
            Goedemorgen
        @elseif(\Carbon\Carbon::now('Europe/Amsterdam')->hour >= 12 && \Carbon\Carbon::now('Europe/Amsterdam')->hour < 17)
            Goedemiddag
        @elseif(\Carbon\Carbon::now('Europe/Amsterdam')->hour >= 17 || \Carbon\Carbon::now('Europe/Amsterdam')->hour < 2)
            Goedenavond
        @else
            Hallo
        @endif
        {{ $authenticatedUser->getUsername() }}
    </div>

    <div class="Hero__buttonsOuterContainer">
        <div class="Hero__buttonContainer">
            <div
                class="Hero__button Hero__button--bordered chatManager"
                v-on:click="toggleManager()"
                title="{{ trans(config('app.directory_name') . '/hero.conversations') }}"
            >
                <span class="material-icons">
                    chat
                </span>
            </div>

            <div class="Hero__button__text">
                <conversations-with-new-messages-count
                    v-if="countConversationsWithNewMessages"
                    :count="countConversationsWithNewMessages"
                >
                </conversations-with-new-messages-count>
            </div>
        </div>
        <div class="Hero__buttonContainer">
            <a
                href="{{ route('credits.show') }}"
                class="Hero__button Hero__button--bordered credits"
                title="{{ trans(config('app.directory_name') . '/hero.credits') }}"
            >
                <span class="material-icons">
                    payments
                </span>
            </a>

            <div class="Hero__button__text">
                <credits-count
                    v-if="userCredits >= 0"
                    :credits="userCredits"
                    :template="'text'"
                >
                </credits-count>
            </div>
        </div>

        <div class="Hero__buttonContainer">
            <a
                href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->getUsername()]) }}"
                class="Hero__button Hero__button--bordered editProfile"
                title="{{ trans(config('app.directory_name') . '/hero.edit_profile') }}"
            >
                <span class="material-icons">
                    build
                </span>
            </a>

            <div class="Hero__button__text">
                <span>
                    {{ $authenticatedUser->profileRatioFilled * 100 }}%
                    ingevuld
                </span>
            </div>
        </div>
    </div>
</div>
