<div class="Tile LatestViewed">
    <div class="Tile__heading LatestViewed__heading">
        {{ trans('viewed_users.viewed_users') }}
    </div>
    <div class="Tile__body LatestViewed__body">
        @foreach($latestViewed as $view)
            <a href="{!! route('users.show', ['username' => $view->viewed->getUsername()]) !!}" class="LatestViewed__item">
                <div class="LatestViewed__profile-image">
                    <img src="{!! \StorageHelper::profileImageUrl($view->viewed, true) !!}" alt="">

                    @if(in_array($view->viewed->getId(), $onlineUserIds))
                        <div class="onlineCircle"></div>
                    @endif
                </div>
                <div class="LatestViewed__username">
                    {{ $view->viewed->username }}{{ $view->viewed->meta->dob ? ', ' : '' }}
                    {{ $view->viewed->meta->dob ? $view->viewed->meta->dob->diffInYears(\Carbon\Carbon::now('Europe/Amsterdam')) : '' }}
                </div>
            </a>
        @endforeach
    </div>
</div>