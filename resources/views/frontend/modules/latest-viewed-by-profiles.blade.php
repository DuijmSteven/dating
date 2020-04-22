<div class="Tile LatestViewed">
    <div class="Tile__heading LatestViewedBy__heading">
        {{ trans('viewed_by_users.viewed_by_users') }}
    </div>
    <div class="Tile__body LatestViewedBy__body">
        @forelse($latestViewedBy as $view)
            <a href="{!! route('users.show', ['username' => $view->viewer->getUsername()]) !!}" class="LatestViewedBy__item">
                <div class="LatestViewedBy__profile-image">
                    <img src="{!! \StorageHelper::profileImageUrl($view->viewer, true) !!}" alt="">

                    @if(in_array($view->viewer->getId(), $onlineUserIds))
                        <div class="onlineCircle"></div>
                    @endif
                </div>
                <div class="LatestViewedBy__username">
                    {{ $view->viewer->username }}{{ $view->viewer->meta->dob ? ', ' : '' }}
                    {{ $view->viewer->meta->dob ? $view->viewer->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                </div>
            </a>
        @empty
            <p>
                {{ trans('viewed_by_users.no_views') }}
            </p>
        @endforelse
    </div>
</div>