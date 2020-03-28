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
        @include('admin.layouts.default.partials.editor-nav-items');
    @elseif(\Auth::user()->isOperator())
        @include('admin.layouts.default.partials.operator-nav-items');
    @elseif(\Auth::user()->isAdmin())
        <ul class="sidebar-menu" data-widget="tree">
            @include('admin.layouts.default.partials.admin-nav-items');
        </ul>
    @else
        Something is wrong with your role situation dude...
    @endif
</section>
<!-- /.sidebar -->
