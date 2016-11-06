<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= asset('backend/dist/img/user2-160x160.jpg'); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
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
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview
                       <?= Request::route()->getName() == 'backend.dashboard' ?
                                                                     'active' :
                                                                     ''; ?>">
                <a href="<?= route('backend.dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview <?= str_contains(\Request::route()->getName(), 'backend.bots') ? 'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-android"></i>
                    <span>Bots</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= \Request::route()->getName() == 'backend.bots.retrieve' ? 'active' : ''; ?>">
                        <a href="<?= route('backend.bots.retrieve'); ?>">
                            <i class="fa fa-list"></i>
                            Overview
                        </a>
                    </li>
                    <li class="<?= \Request::route()->getName() == 'backend.bots.create.get' ? 'active' : ''; ?>">
                        <a href="<?= route('backend.bots.create.get'); ?>">
                            <i class="fa fa-user"></i>
                            Create
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?= route('home'); ?>">
                    <i class="fa fa-globe"></i>
                    <span>Frontend</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
