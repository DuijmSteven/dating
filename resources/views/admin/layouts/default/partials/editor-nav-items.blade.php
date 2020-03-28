<ul class="sidebar-menu">
    <li class="treeview {!! str_contains(\Request::route()->getName(), 'editors.bots') ? 'active' : '' !!}">
        <a href="#">
            <i class="fa fa-android"></i>
            <span>Bots</span>
            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            <li class="{!! \Request::route()->getName() == 'editors.bots.created.overview' ? 'active' : '' !!}">
                <a href="{!! route('editors.bots.created.overview') !!}">
                    <i class="fa fa-list"></i>
                    Overview
                </a>
            </li>
            <li class="{!! \Request::route()->getName() == 'editors.bots.create.get' ? 'active' : '' !!}">
                <a href="{!! route('editors.bots.create.get') !!}">
                    <i class="fa fa-user-plus"></i>
                    Create
                </a>
            </li>
            <li class="{!! \Request::route()->getName() == 'editors.bots.map.show' ? 'active' : '' !!}">
                <a href="{!! route('editors.bots.map.show') !!}">
                    <i class="fa fa-map"></i>
                    On Map
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{!!  route('logout.post') !!}"
           onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out"></i>
            <span>Log out</span>
        </a>

        <form id="logout-form" action="{!!  route('logout.post') !!}" method="POST"
              style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
</ul>

<div class="navbarUserStats">
    <h5 class="statsHeading"><strong>Created bots</strong></h5>
    <div class="statsBody">
        <strong>All time:</strong> {!! $authenticatedUser->created_bots_count !!} <br>
        <strong>Last month:</strong> {!! $authenticatedUser->created_bots_last_month_count !!} <br>
        <strong>This month:</strong> {!! $authenticatedUser->created_bots_this_month_count !!} <br>
        <strong>Last week:</strong> {!! $authenticatedUser->created_bots_last_week_count !!} <br>
        <strong>This week:</strong> {!! $authenticatedUser->created_bots_this_week_count !!} <br>
        <strong>Yesterday:</strong> {!! $authenticatedUser->created_bots_yesterday_count !!} <br>
        <strong>Today:</strong> {!! $authenticatedUser->created_bots_today_count !!} <br>
    </div>
</div>