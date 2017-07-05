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
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.peasants') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Peasants</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.peasants.retrieve' ? 'active' : '' !!}">
            <a href="{!! route('admin.peasants.retrieve') !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.peasants.create.get' ? 'active' : '' !!}">
            <a href="{!! route('admin.peasants.create.get') !!}">
                <i class="fa fa-user-plus"></i>
                Create
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.payments') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-money"></i>
        <span>Payments</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.payments.index' ? 'active' : '' !!}">
            <a href="{!! route('admin.payments.index', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.conversations') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-comments"></i>
        <span>Conversations</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.peasants.retrieve' ? 'active' : '' !!}">
            <a href="{!! route('admin.conversations.index', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.articles') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-newspaper-o"></i>
        <span>Articles</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.articles.index' ? 'active' : '' !!}">
            <a href="{!! route('admin.articles.index', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.articles.create' ? 'active' : '' !!}">
            <a href="{!! route('admin.articles.create') !!}">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'operators_platform') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Operators</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'operators_platform.dashboard' ? 'active' : '' !!}">
            <a href="{!! route('operators_platform.dashboard') !!}">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.modules') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-money"></i>
        <span>Modules</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.modules.index' ? 'active' : '' !!}">
            <a href="{!! route('admin.modules.index', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.modules.layout.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.modules.layout.show') !!}">
                <i class="fa fa-list"></i>
                Layout
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.views') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-money"></i>
        <span>Views</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.views.overview' ? 'active' : '' !!}">
            <a href="{!! route('admin.views.overview', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.views.create.show' !!}">
            <a href="{!! route('admin.views.create.show') !!}">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="{!! route('home') !!}">
        <i class="fa fa-globe"></i>
        <span>Frontend</span>
    </a>
</li>