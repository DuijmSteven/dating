<div class="Tile LatestViewed ModuleProfiles JS--Tile Tile--withToggle">
    <div class="Tile__heading LatestViewedBy__heading JS--Tile__toggle">
        @include('frontend.modules.partials.sites.' . config('app.directory_name') . '.viewed-profiles-heading-icon')

        {{ trans('viewed_by_users.viewed_by_users_backup') }}

{{--        @include('frontend.components.tile-toggle')--}}

        <div class="Tile__toggle JS--Tile__toggleExpand hidden">
            <span class="material-icons">
                expand_more
            </span>
        </div>

        <div class="Tile__toggle JS--Tile__toggleCollapse">
            <span class="material-icons">
                expand_less
            </span>
        </div>

    </div>
    <div class="Tile__body LatestViewedBy__body ModuleProfiles__body JS--Tile__body">
        @include('frontend.modules.partials.sites.' . config('app.directory_name') . '.profiles', ['users' => $users])
    </div>
</div>