<div class="Tile LatestViewed JS--Tile Tile--withToggle">
    <div class="Tile__heading LatestViewedBy__heading JS--Tile__toggle">
        <span class="material-icons">
            remove_red_eye
        </span>

        {{ trans('viewed_by_users.viewed_by_users') }}

        @include('frontend.components.tile-toggle')
    </div>
    <div class="Tile__body LatestViewedBy__body JS--Tile__body">
        @forelse($latestViewedBy as $view)
            <div class="LatestViewedBy__bodyWrapper">
                <a href="{!! route('users.show', ['username' => $view->viewer->getUsername()]) !!}" class="LatestViewedBy__item">
                    <span class="LatestViewedBy__profile-image">
                        <img src="{!! \StorageHelper::profileImageUrl($view->viewer, true) !!}" alt="">

                        @if(in_array($view->viewer->getId(), $onlineUserIds))
                            <div class="onlineCircle"></div>
                        @endif
                    </span>
                    <span class="LatestViewedBy__username">
                        {{ $view->viewer->username }}{{ $view->viewer->meta->dob ? ', ' : '' }}
                        {{ $view->viewer->meta->dob ? $view->viewer->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                    </span>
                </a>

                <div class="LatestViewedBy__sendMessage"
                     v-on:click="addChat({!! $authenticatedUser->getId() !!}, {!! $view->viewer->getId() !!}, '1', true)"
                >
                    <i class="material-icons material-icon LatestViewedBy__sendMessage__icon">textsms</i>
                </div>
            </div>
        @empty
            <p>
                {{ trans('viewed_by_users.no_views') }}
            </p>
        @endforelse
    </div>
</div>