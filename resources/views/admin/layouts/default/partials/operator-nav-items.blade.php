<ul class="sidebar-menu">
    <li class="treeview
                       {!! Request::route()->getName() == 'operator-platform.dashboard' ?
                    'active' :
                    '' !!}">
        <a href="{!! route('operator-platform.dashboard') !!}">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
        </a>
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
    <h5 class="statsHeading"><strong>Messages sent</strong></h5>
    <div class="statsBody">
        <strong>All time:</strong> {!! $authenticatedUser->operator_messages_count !!} <br>
        <strong>Last month:</strong> {!! $authenticatedUser->operator_messages_last_month_count !!} <br>
        <strong>This month:</strong> {!! $authenticatedUser->operator_messages_this_month_count !!} <br>
        <strong>Last week:</strong> {!! $authenticatedUser->operator_messages_last_week_count !!} <br>
        <strong>This week:</strong> {!! $authenticatedUser->operator_messages_this_week_count !!} <br>
        <strong>Yesterday:</strong> {!! $authenticatedUser->operator_messages_yesterday_count !!} <br>
        <strong>Today:</strong> {!! $authenticatedUser->operator_messages_today_count !!} <br>
    </div>
</div>
