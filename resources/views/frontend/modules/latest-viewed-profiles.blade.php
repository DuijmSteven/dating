<div class="Tile LatestViewed">
    <div class="Tile__heading LatestViewed__heading">
        <span class="material-icons">
            remove_red_eye
        </span>

        {{ trans('viewed_users.viewed_users') }}
    </div>
    <div class="Tile__body LatestViewed__body">
        @forelse($latestViewed as $view)
            <div class="LatestViewed__bodyWrapper">

                <a href="{!! route('users.show', ['username' => $view->viewed->getUsername()]) !!}" class="LatestViewed__item">
                    <span class="LatestViewed__profile-image">
                        <img src="{!! \StorageHelper::profileImageUrl($view->viewed, true) !!}" alt="">

                        @if(in_array($view->viewed->getId(), $onlineUserIds))
                            <div class="onlineCircle"></div>
                        @endif
                    </span>
                    <span class="LatestViewed__username">
                        {{ $view->viewed->username }}{{ $view->viewed->meta->dob ? ', ' : '' }}
                        {{ $view->viewed->meta->dob ? $view->viewed->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                    </span>
                </a>

                <div class="LatestViewed__sendMessage"
                     v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $view->viewed->getId() !!}, '1', true)"
                >
                    <i class="material-icons material-icon LatestViewed__sendMessage__icon">textsms</i>
                </div >
            </div>
        @empty
            <p>
                {{ trans('viewed_users.no_views') }}
            </p>
        @endforelse
    </div>
</div>