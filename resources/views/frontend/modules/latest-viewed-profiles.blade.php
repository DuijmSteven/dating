<div class="Tile LatestViewed ModuleProfiles JS--Tile Tile--withToggle">
    <div class="Tile__heading LatestViewed__heading JS--Tile__toggle">
        @include('frontend.modules.partials.sites.' . config('app.directory_name') . '.viewed-profiles-heading-icon')

        {{ trans('viewed_users.viewed_users_backup') }}

        @include('frontend.components.tile-toggle')
    </div>
    <div class="Tile__body LatestViewed__body ModuleProfiles__body JS--Tile__body">
        @include('frontend.modules.partials.sites.' . config('app.directory_name') . '.profiles', ['users' => $users])
    </div>
</div>