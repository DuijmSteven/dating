<div class="Tile Online-users ModuleProfiles JS--Tile Tile--withToggle">
    <div class="Tile__heading Online-users__heading JS--Tile__toggle">
        <span class="onlineCircle blinking"></span>

        <span class="Online-users__headingText">{{ trans('online_users.online_users_backup') }}</span>

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
    <div class="Tile__body Online-users__body ModuleProfiles__body JS--Tile__body">
        @include('frontend.modules.partials.sites.' . config('app.directory_name') . '.profiles', ['users' => $users])
    </div>
</div>