<div class="Tile UserSummary {{ isset($firstTile) ?? $firstTile ? 'firstTile' : '' }}" data-user-id="{!! $user->getId() !!}">
    <div class="Tile__body UserSummary__body">
        <div class="UserSummary__user-image">
            @if(isset($showOtherImages))
                @if($user->hasProfileImage())
                    <a href="#" class="modalImage">
                        <img
                            class="UserSummary__profileImage JS--galleryImage"
                            src="{{ $user->profileImageUrl }}"
                            data-src="{{ $user->profileImageUrl }}"
                            alt="user image"
                        >
                    </a>
                @else
                    <div class="UserSummary__profileImageWrapper">
                        <img
                            class="UserSummary__profileImage"
                            src="{{ $user->profileImageUrl }}"
                            alt="user image"
                        >
                    </div>
                @endif
            @else
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                    <img
                        class="UserSummary__profileImage JS--galleryImage"
                        src="{{ $user->profileImageUrl }}"
                        alt="user image"
                    >
                </a>
            @endif

            @if(in_array($user->getId(), $onlineUserIds))
                <div class="onlineCircle blinking"></div>
            @endif
        </div>
    </div>
    <div class="Tile__footer UserSummary__footer">
        <div class="UserSummary__footer__upperPart">
            @if(!isset($noInfo) || (isset($noInfo) && $noInfo === false))
                <div class="UserSummary__userInfo">
                    <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}"
                       class="UserSummary__userInfo__primary">
                        {{ $user->username }}

                        @if($user->meta->dob)
                            {!! ' <span>-</span> ' !!}
                        @endif

                        {!! $user->meta->dob ? $user->meta->dob->diffInYears($carbonNow) : '' !!}

                        @if(in_array($user->getId(), $onlineUserIds))
                            <div class="onlineCircle"></div>
                        @endif
                    </a>
                    <div class="UserSummary__userInfo__additional">
                        @if(isset($user->meta->city))
                            {{ ucfirst($user->meta->city) }}
                        @endif
                    </div>

                    @if(isset($showAboutMe) && $showAboutMe && $user->meta->getAboutMe())
                        <div class="UserSummary__aboutMe">
                            "{!! $user->meta->getAboutMe() !!}"
                        </div>
                    @endif
                </div>
            @endif

            <div class="UserSummary__footer__upperPart__buttons">
                @if(!isset($noButton) || (isset($noButton) && $noButton === false))
                    <div
                        class="UserSummary__sendMessage"
                        v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
                    >
                        <i class="material-icons material-icon UserSummary__sendMessage__icon">chat_bubble</i>
                        <span class="UserSummary__sendMessage__text">
                            {{ trans(config('app.directory_name') . '/user_profile.chat') }}
                        </span>
                    </div>
                @endif

                @if(!isset($showOtherImages))
                    <a
                       class="UserSummary__seeProfile"
                       href="{{ route('users.show', ['username' => $user->getUsername()])  }}"
                    >
                        <span class="material-icons UserSummary__seeProfile__icon">
                            account_circle
                        </span>
                        {{ trans(config('app.directory_name') . '/user_profile.see_profile') }}
                    </a>
                @endif
            </div>
        </div>

        @if(isset($showOtherImages) && $showOtherImages)
            <div class="UserSummary__otherImages">
                {{-- DON'T reformat this loop, it is structured like this to avoid spacing between inline blocks --}}
                @foreach($user->visibleImagesNotProfile as $image)<a href="#" class="modalImage UserSummary__nonProfileImageModalWrapper"><div class="UserSummary__nonProfileImageWrapper"><img
                            {{--                                class="UserSummary__nonProfileImage JS--galleryImage {{ !$authenticatedUser->isPayingUser() ? 'blurred' : '' }}"--}}
                            class="UserSummary__nonProfileImage JS--galleryImage"
                            src="{{ $image->url }}"
                            data-src="{{ $image->url }}"
                            alt="user image"
                        ></div></a>@endforeach


            </div>
        @endif
    </div>
</div>