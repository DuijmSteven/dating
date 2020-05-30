<li class="{!! \Request::route()->getName() == 'admin.dashboard' ? 'active' : '' !!}">
    <a href="{!! route('admin.dashboard') !!}">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
    </a>
</li>

<li class="{!! \Request::route()->getName() == 'admin.statistics' ? 'active' : '' !!}">
    <a href="{!! route('admin.statistics') !!}">
        <i class="fa fa-dashboard"></i>
        <span>Statistics</span>
    </a>
</li>

<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.bots') ? 'active' : '' !!}">
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
        <li class="{!! \Request::route()->getName() == 'admin.bots.online.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.bots.online.show') !!}">
                <i class="fa fa-circle"></i>
                Online
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
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.peasants') ? 'active' : '' !!}">
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
        <li class="{!! \Request::route()->getName() == 'admin.peasants.online.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.peasants.online.show') !!}">
                <i class="fa fa-circle"></i>
                Online
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.peasants.create.get' ? 'active' : '' !!}">
            <a href="{!! route('admin.peasants.create.get') !!}">
                <i class="fa fa-user-plus"></i>
                Create
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.peasants.map.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.peasants.map.show') !!}">
                <i class="fa fa-map"></i>
                On Map
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.peasants.deactivations.overview' ? 'active' : '' !!}">
            <a href="{!! route('admin.peasants.deactivations.overview') !!}">
                <i class="fa fa-list"></i>
                Deactivations
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.operators') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Operators</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.operators.overview' ? 'active' : '' !!}">
            <a href="{!! route('admin.operators.overview') !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.operators.online.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.operators.online.show') !!}">
                <i class="fa fa-circle"></i>
                Online
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.operators.create.get' ? 'active' : '' !!}">
            <a href="{!! route('admin.operators.create.get') !!}">
                <i class="fa fa-user-plus"></i>
                Create
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.editors') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-edit"></i>
        <span>Editors</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.editors.overview' ? 'active' : '' !!}">
            <a href="{!! route('admin.editors.overview') !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
        <li class="{!! \Request::route()->getName() == 'admin.editors.online.show' ? 'active' : '' !!}">
            <a href="{!! route('admin.editors.online.show') !!}">
                <i class="fa fa-circle"></i>
                Online
            </a>
        </li>
        {{--         <li class="{!! \Request::route()->getName() == 'admin.bots.create.get' ? 'active' : '' !!}">--}}
        {{--             <a href="{!! route('admin.bots.create.get') !!}">--}}
        {{--                 <i class="fa fa-user-plus"></i>--}}
        {{--                 Create--}}
        {{--             </a>--}}
        {{--         </li>--}}
    </ul>
</li>
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.payments') ? 'active' : '' !!}">
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
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.mass-messages') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-comments"></i>
        <span>Mass messages</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.mass-messages.new' ? 'active' : '' !!}">
            <a href="{!! route('admin.mass-messages.new') !!}">
                <i class="fa fa-list"></i>
                New mass message
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.conversations') ? 'active' : '' !!}">
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
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.messages') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-comments"></i>
        <span>Messages</span>
        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{!! \Request::route()->getName() == 'admin.messages.overview' ? 'active' : '' !!}">
            <a href="{!! route('admin.messages.overview', ['page' => 1]) !!}">
                <i class="fa fa-list"></i>
                Overview
            </a>
        </li>
    </ul>
</li>
<li class="treeview {!! \Str::contains(\Request::route()->getName(), 'operator-platform') ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-users"></i>
        <span>Operator platform</span>
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
        return \Str::contains($routeName, $value);
    }, [
        'admin.modules',
        'admin.layout',
        'admin.views',
        'admin.layout-parts',
    ]);
@endphp
<li class="treeview {!! in_array(true, $lmsRoutesTruthArray) ? 'active' : '' !!}">
    <a href="#">
        <i class="fa fa-table"></i>
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.modules') ? 'active' : '' !!}">
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.views') ? 'active' : '' !!}">
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.layout-parts') ? 'active' : '' !!}">
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
        return \Str::contains($routeName, $value);
    }, [
        'admin.faqs',
        'admin.tacs',
        'admin.articles',
        'admin.testimonials',
        'admin.testimonial-users',
        'admin.bot-messages'
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.bot-messages') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-comments-o"></i>
                <span>Bot Messages</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.bot-messages.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.bot-messages.overview', ['page' => 1]) !!}">
                        <i class="fa fa-list"></i>
                        Overview
                    </a>
                </li>
                <li class="{!! \Request::route()->getName() == 'admin.bot-messages.create' ? 'active' : '' !!}">
                    <a href="{!! route('admin.bot-messages.create') !!}">
                        <i class="fa fa-plus"></i>
                        Create
                    </a>
                </li>
            </ul>
        </li>

        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.articles') ? 'active' : '' !!}">
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.faqs') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-question-circle"></i>
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.testimonials') ? 'active' : '' !!}">
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
        <li class="treeview {!! \Str::contains(\Request::route()->getName(), 'admin.tacs') ? 'active' : '' !!}">
            <a href="#">
                <i class="fa fa-newspaper-o"></i>
                <span>Tacs</span>
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>
            <ul class="treeview-menu">
                <li class="{!! \Request::route()->getName() == 'admin.tacs.overview' ? 'active' : '' !!}">
                    <a href="{!! route('admin.tacs.overview', ['page' => 1]) !!}">
                        <i class="fa fa-list"></i>
                        Overview
                    </a>
                </li>
                <li class="{!! \Request::route()->getName() == 'admin.tacs.create' ? 'active' : '' !!}">
                    <a href="{!! route('admin.tacs.create') !!}">
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