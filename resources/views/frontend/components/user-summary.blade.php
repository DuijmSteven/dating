<div class="Tile UserSummary" data-user-id="{!! $user->getId() !!}">
    <div class="Tile__body UserSummary__body">
        <div class="UserSummary__user-image">
            @if(isset($showOtherImages))
                @if($user->hasProfileImage())
                    <a href="#" class="modalImage">
                        <img
                            class="UserSummary__profileImage JS--galleryImage"
                            src="{{ \StorageHelper::profileImageUrl($user) }}"
                            data-src="{{ \StorageHelper::profileImageUrl($user) }}"
                            alt="user image"
                        >
                    </a>
                @else
                    <div class="UserSummary__profileImageWrapper">
                        <img
                            class="UserSummary__profileImage"
                            src="{{ \StorageHelper::profileImageUrl($user) }}"
                            alt="user image"
                        >
                    </div>
                @endif
            @else
                <a href="{{ route('users.show', ['username' => $user->getUsername()])  }}">
                    <img
                        class="UserSummary__profileImage JS--galleryImage"
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
                 v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $user->getId() !!}, '1', true)"
            >
                <span class="UserSummary__sendMessage__text">Chat</span>
                <i class="material-icons material-icon UserSummary__sendMessage__icon">textsms</i>
            </div>
        </div>

        @if(isset($showOtherImages) && $showOtherImages)
            <div class="UserSummary__otherImages">
                {{-- DON'T reformat this loop, it is structured like this to avoid spacing between inline blocks --}}
                @foreach($user->imagesNotProfile as $image)<a href="#" class="modalImage UserSummary__nonProfileImageModalWrapper"><div class="UserSummary__nonProfileImageWrapper"><img
                                class="UserSummary__nonProfileImage JS--galleryImage"
                                src="{{ \StorageHelper::userImageUrl($user->getId(), $image->getFilename()) }}"
                                data-src="{{ \StorageHelper::userImageUrl($user->getId(), $image->getFilename()) }}"
                                alt="user image"
                            ></div></a>@endforeach
            </div>
        @endif
    </div>
</div>