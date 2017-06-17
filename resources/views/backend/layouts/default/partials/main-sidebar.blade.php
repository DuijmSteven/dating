<aside class="main-sidebar">
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
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        @if(\Auth::user()->isOperator())
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview
                           {!! Request::route()->getName() == 'operators_platform.dashboard' ?
                        'active' :
                        '' !!}">
                    <a href="{!! route('operators_platform.dashboard') !!}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        @elseif(\Auth::user()->isAdmin())
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview
                           {!! Request::route()->getName() == 'backend.dashboard' ?
                                                                         'active' :
                                                                         '' !!}">
                    <a href="{!! route('backend.dashboard') !!}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="treeview {!! str_contains(\Request::route()->getName(), 'backend.bots') ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-android"></i>
                        <span>Bots</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{!! \Request::route()->getName() == 'backend.bots.retrieve' ? 'active' : '' !!}">
                            <a href="{!! route('backend.bots.retrieve') !!}">
                                <i class="fa fa-list"></i>
                                Overview
                            </a>
                        </li>
                        <li class="{!! \Request::route()->getName() == 'backend.bots.create.get' ? 'active' : '' !!}">
                            <a href="{!! route('backend.bots.create.get') !!}">
                                <i class="fa fa-user-plus"></i>
                                Create
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {!! str_contains(\Request::route()->getName(), 'backend.peasants') ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>Peasants</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{!! \Request::route()->getName() == 'backend.peasants.retrieve' ? 'active' : '' !!}">
                            <a href="{!! route('backend.peasants.retrieve') !!}">
                                <i class="fa fa-list"></i>
                                Overview
                            </a>
                        </li>
                        <li class="{!! \Request::route()->getName() == 'backend.peasants.create.get' ? 'active' : '' !!}">
                            <a href="{!! route('backend.peasants.create.get') !!}">
                                <i class="fa fa-user-plus"></i>
                                Create
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {!! str_contains(\Request::route()->getName(), 'backend.payments') ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-money"></i>
                        <span>Payments</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{!! \Request::route()->getName() == 'backend.payments.index' ? 'active' : '' !!}">
                            <a href="{!! route('backend.payments.index', ['page' => 1]) !!}">
                                <i class="fa fa-list"></i>
                                Overview
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {!! str_contains(\Request::route()->getName(), 'backend.conversations') ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-comments"></i>
                        <span>Conversations</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{!! \Request::route()->getName() == 'backend.peasants.retrieve' ? 'active' : '' !!}">
                            <a href="{!! route('backend.conversations.index', ['page' => 1]) !!}">
                                <i class="fa fa-list"></i>
                                Overview
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview {!! str_contains(\Request::route()->getName(), 'backend.articles') ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-newspaper-o"></i>
                        <span>Articles</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{!! \Request::route()->getName() == 'backend.articles.index' ? 'active' : '' !!}">
                            <a href="{!! route('backend.articles.index', ['page' => 1]) !!}">
                                <i class="fa fa-list"></i>
                                Overview
                            </a>
                        </li>
                        <li class="{!! \Request::route()->getName() == 'backend.articles.create' ? 'active' : '' !!}">
                            <a href="{!! route('backend.articles.create') !!}">
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
                <li class="treeview {!! str_contains(\Request::route()->getName(), 'backend.modules') ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-money"></i>
                        <span>Modules</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{!! \Request::route()->getName() == 'backend.modules.index' ? 'active' : '' !!}">
                            <a href="{!! route('backend.modules.index', ['page' => 1]) !!}">
                                <i class="fa fa-list"></i>
                                Overview
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="{!! route('home') !!}">
                        <i class="fa fa-globe"></i>
                        <span>Frontend</span>
                    </a>
                </li>
            </ul>
        @else
            Something is wrong with your role situation dude...
        @endif
    </section>
    <!-- /.sidebar -->
</aside>
