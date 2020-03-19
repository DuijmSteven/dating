<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ \StorageHelper::profileImageUrl($authenticatedUser) }}"
                 class="img-rounded"
                 alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{ $authenticatedUser->username }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>

    @if(\Auth::user()->isEditor())
        <ul class="sidebar-menu">
            <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.bots') ? 'active' : '' !!}">
                <a href="#">
                    <i class="fa fa-android"></i>
                    <span>Bots</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! \Request::route()->getName() == 'admin.bots.retrieve' ? 'active' : '' !!}">
                        <a href="{!! route('admin.bots.retrieve') !!}">
                            <i class="fa fa-list"></i>
                            Overview
                        </a>
                    </li>
                    <li class="{!! \Request::route()->getName() == 'admin.bots.create.get' ? 'active' : '' !!}">
                        <a href="{!! route('admin.bots.create.get') !!}">
                            <i class="fa fa-user-plus"></i>
                            Create
                        </a>
                    </li>
                    <li class="{!! \Request::route()->getName() == 'admin.bots.map.show' ? 'active' : '' !!}">
                        <a href="{!! route('admin.bots.map.show') !!}">
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
    @elseif(\Auth::user()->isOperator())
        <ul class="sidebar-menu">
{{--            <li class="header">MAIN NAVIGATION</li>--}}
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
    @elseif(\Auth::user()->isAdmin())
        <ul class="sidebar-menu" data-widget="tree">
{{--
            <li class="header">MAIN NAVIGATION</li>
--}}
            @include('admin.layouts.default.partials.main-sidebar-nav-items');
        </ul>
    @else
        Something is wrong with your role situation dude...
    @endif
</section>
<!-- /.sidebar -->
