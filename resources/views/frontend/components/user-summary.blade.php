<div class="Tile UserSummary" data-user-id="{!! $user->getId() !!}">
    {{--    <div class="Tile__heading UserSummary__heading">
            <a href="{{ route('users.show', ['userId' => $user->getId()])  }}">
                {{ $user->username }}{{ isset($user->meta->dob) ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}
            </a>
        </div>--}}
    <div class="Tile__body UserSummary__body JS--UserSummary">
        <div class="UserSummary__user-image JS--UserSummary__user-image">
            @if($user->hasProfileImage())
                @if(isset($showOtherImages))
                    <a href="#" class="modalImage">
                        <div class="UserSummary__profileImageWrapper">
                            <img
                                class="UserSummary__profileImage"
                                src="{{ \StorageHelper::profileImageUrl($user) }}"
                                alt="user image"
                            >
                        </div>
                    </a>
                @else
                    <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                        <div class="UserSummary__profileImageWrapper">
                            <img
                                class="UserSummary__profileImage"
                                src="{{ \StorageHelper::profileImageUrl($user) }}"
                                alt="user image"
                            >
                        </div>
                    </a>
                @endif
            @else
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                    <img
                        src="{{ \StorageHelper::profileImageUrl($user) }}"
                        alt="user image"
                    >
                </a>
            @endif
        </div>
    </div>
    <div class="Tile__footer UserSummary__footer">
        <div class="UserSummary__footer__upperPart">
            <div class="UserSummary__userInfo">
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}"
                   class="UserSummary__userInfo__primary">
                    {{ $user->username }}
                </a>
                <div class="UserSummary__userInfo__additional">
                    {!! isset($user->meta->city) ? $user->meta->city . " <span>&centerdot;</span>" : '' !!}
                    {{ $user->meta->dob->diffInYears($carbonNow) }} Jaar
                </div>
            </div>
            <div class="UserSummary__sendMessage"
                 v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)">
                <i class="material-icons material-icon UserSummary__sendMessage__icon">textsms</i>
            </div>
        </div>

        @if(isset($showOtherImages) && $showOtherImages)
            <div class="UserSummary__otherImages JS--UserSummary__otherImages">
                {{-- DON'T reformat this loop, it is structured like this to avoid spacing between inline blocks --}}
                @foreach($user->imagesNotProfile as $image)<a href="#" class="modalImage UserSummary__nonProfileImageModalWrapper"><div class="UserSummary__nonProfileImageWrapper JS--UserSummary__nonProfileImageWrapper"><img
                                class="UserSummary__nonProfileImage"
                                src="{{ \StorageHelper::userImageUrl($user->getId(), $image->getFilename()) }}"
                                alt="user image"
                            ></div></a>@endforeach
            </div>
        @endif
    </div>
</div>