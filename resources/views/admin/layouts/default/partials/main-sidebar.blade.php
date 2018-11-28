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
                       {!! Request::route()->getName() == 'operator-platform.dashboard' ?
                    'active' :
                    '' !!}">
                <a href="{!! route('operator-platform.dashboard') !!}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
    @elseif(\Auth::user()->isAdmin())
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            @include('admin.layouts.default.partials.main-sidebar-nav-items');
        </ul>
    @else
        Something is wrong with your role situation dude...
    @endif
</section>
<!-- /.sidebar -->
