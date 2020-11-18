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
        @elseif(\Carbon\Carbon::now('Europe/Amsterdam')->hour >= 17 && \Carbon\Carbon::now('Europe/Amsterdam')->hour < 2)
            Goedenavond
        @else
            Hallo
        @endif
        {{ $authenticatedUser->getUsername() }}
    </div>

    <div class="Hero__buttonsContainer">
        <div
            class="Hero__button chatManager"
            v-on:click="toggleManager()"
            title="Gespreken"
        >
            <span class="material-icons">
                chat
            </span>
        </div>

        <div
            class="Hero__button credits"
            title="Koop Credits"
        >
            <span class="material-icons">
                credit_card
            </span>

            <credits-count
                v-if="userCredits"
                :credits="userCredits"
                :template="'disk'"
            >
            </credits-count>
        </div>
    </div>
</div>
