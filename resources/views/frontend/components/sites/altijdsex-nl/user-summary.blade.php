<div class="Tile UserSummary" data-user-id="{!! $user->getId() !!}">
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
                <div class="onlineCircle"></div>
            @endif
        </div>
    </div>
    <div class="Tile__footer UserSummary__footer">
        <div class="UserSummary__footer__upperPart">
            <div class="UserSummary__userInfo">
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}"
                   class="UserSummary__userInfo__primary">
                    {{ $user->username }}

                    @if(in_array($user->getId(), $onlineUserIds))
                        <div class="onlineCircle"></div>
                    @endif
                </a>
                <div class="UserSummary__userInfo__additional">
                    @if(isset($user->meta->city) && $user->meta->dob)
                        {!! ucfirst($user->meta->city) . ' <span>&centerdot;</span> ' !!}
                    @elseif ($user->meta->city)
                        {{ ucfirst($user->meta->city) }}
                    @endif

                    {!! $user->meta->dob ? $user->meta->dob->diffInYears($carbonNow) . ' Jaar' : '' !!}

                    {!! !isset($user->meta->city) && !$user->meta->dob ? '&nbsp' : '' !!}

                </div>

                @if(isset($showAboutMe) && $showAboutMe && $user->meta->getAboutMe())
                    <div class="UserSummary__aboutMe">
                        "{!! $user->meta->getAboutMe() !!}"
                    </div>
                @endif

            </div>
            <div class="UserSummary__sendMessage"
                 v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
            >
                <span class="UserSummary__sendMessage__text">Bericht</span>
                <i class="material-icons material-icon UserSummary__sendMessage__icon">textsms</i>
            </div >

            @if(!isset($showOtherImages))
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}" class="UserSummary__seeProfile">{{ trans('user_profile.see_profile') }}</a>
            @endif
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