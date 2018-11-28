<li class="{!! \Request::route()->getName() == 'admin.dashboard' ? 'active' : '' !!}">
    <a href="{!! route('admin.dashboard') !!}">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
    </a>
</li>

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
        <li class="{!! \Request::route()->getName() == 'admin.payments.overview' ? 'active' : '' !!}">
            <a href="{!! route('admin.payments.overview', ['page' => 1]) !!}">
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
            <a href="{!! route('admin.conversations.overview', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! str_contains(\Request::route()->getName(), 'operator-platform') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Operators</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'operator-platform.dashboard' ? 'active' : '' !!}">
            <a href="{!! route('operator-platform.dashboard') !!}">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</li>
@php
    $routeName = \Request::route()->getName();
    $lmsRoutesTruthArray = array_map(function ($value) use ($routeName) {
        return str_contains($routeName, $value);
    }, [
        'admin.modules',
        'admin.layout',
        'admin.views',
        'admin.layout-parts',
    ]);
@endphp
<li class="treeview {!! in_array(true, $lmsRoutesTruthArray) ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-wordpress"></i>
        <span>Layout Management</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.layout.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.layout.show') !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.modules') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-cubes"></i>
                <span>Modules</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.modules.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.modules.overview', ['page' => 1]) !!}">
                        <i class="fa fa-list"></i>
                        Overview
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.views') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-eye"></i>
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
        <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.layout-parts') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-object-group"></i>
                <span>Layout Parts</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.layout-parts.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.layout-parts.overview', ['page' => 1]) !!}">
                        <i class="fa fa-list"></i>
                        Overview
                    </a>
                </li>
                <li class="{!! \Request::route()->getName() == 'admin.layout-parts.create' ? 'active' : '' !!}">
                    <a href="{!! route('admin.layout-parts.create') !!}">
                        <i class="fa fa-plus"></i>
                        Create
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>

@php
    $cmsRoutesTruthArray = array_map(function ($value) use ($routeName) {
        return str_contains($routeName, $value);
    }, [
        'admin.faq',
        'admin.articles',
        'admin.testimonials',
        'admin.testimonial-users',
    ]);
@endphp
<li class="treeview {!! in_array(true, $cmsRoutesTruthArray) ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-wordpress"></i>
        <span>Content Management</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.articles') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-newspaper-o"></i>
                <span>Articles</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.articles.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.articles.overview', ['page' => 1]) !!}">
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
        <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.faqs') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-newspaper-o"></i>
                <span>Faqs</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.faqs.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.faqs.overview', ['page' => 1]) !!}">
                        <i class="fa fa-list"></i>
                        Overview
                    </a>
                </li>
                <li class="{!! \Request::route()->getName() == 'admin.faqs.create' ? 'active' : '' !!}">
                    <a href="{!! route('admin.faqs.create') !!}">
                        <i class="fa fa-plus"></i>
                        Create
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview {!! str_contains(\Request::route()->getName(), 'admin.testimonials') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-quote-left"></i>
                <span>Testimonials</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.testimonials.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.testimonials.overview', ['page' => 1]) !!}">
                        <i class="fa fa-list"></i>
                        Overview
                    </a>
                </li>
                <li class="{!! \Request::route()->getName() == 'admin.testimonials.create' ? 'active' : '' !!}">
                    <a href="{!! route('admin.testimonials.create') !!}">
                        <i class="fa fa-plus"></i>
                        Create
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>

<li>
    <a href="{!! route('home') !!}">
        <i class="fa fa-globe"></i>
        <span>Frontend</span>
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