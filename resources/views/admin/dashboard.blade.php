@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="row" style="margin-bottom: 20px">
                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
                    <a href="{{ route('admin.bots.online.show') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-info">
                            <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $numberOfOnlineBots }} bots online</b></span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
                    <a href="{{ route('admin.peasants.online.show') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-pinkish">
                            <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $numberOfOnlinePeasants }} peasants online</b></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @php
            $amountOfListItemsVisible = 5;
        @endphp

        @foreach([
                'today',
                'this_week',
                'this_month',
            ]
            as $topMessagersWidget
        )

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="box box-widget DashboardWidget expandable">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-primary">
                        <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                            <i class="fa fa-users DashboardWidget_header-icon"></i>
                            <span class="DashboardWidget_header-title">Top messagers {{ str_replace('_', ' ', $topMessagersWidget) }}</span>
                        </div>
                        <!-- /.widget-user-image -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            @php
                                $count = 0;
                            @endphp

                            @foreach($topMessagerStatistics[$topMessagersWidget] as $user)
                                <li class="{{ $count >= $amountOfListItemsVisible ? 'hidden defaultHidden' : '' }}">
                                    @php
                                        $highlightTypeClass = '';

                                        if ($user->account->getCredits() > 10) {
                                            $highlightTypeClass = 'success';
                                        } else if ($user->account->credits > 4) {
                                            $highlightTypeClass = 'warning';
                                        } else {
                                            $highlightTypeClass = 'error';
                                        }
                                    @endphp

                                    <a href="{{ route('admin.peasants.edit.get', ['peasantId' => $user->getId()]) }}">
                                        {{ $user->getUsername() }} (ID: {{ $user->getId() }}) - <strong>Credits: <span class="highlightAsDisk {{ $highlightTypeClass }}">{{ $user->account->getCredits() }}</span></strong>
                                        <span class="DashboardWidget_count">{{ $user->messages->count() }}</span>
                                    </a>
                                </li>

                                @php
                                    $count++;
                                @endphp
                            @endforeach

                            @if(count($topMessagerStatistics[$topMessagersWidget]) > $amountOfListItemsVisible)
                                <li>
                                    <a class="showMore" href="#">
                                        Show more <i class="fa fa-chevron-down"></i>
                                    </a>

                                    <a class="showLess hidden" href="#">
                                        Show less <i class="fa fa-chevron-up"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach([
        'today',
        'this_week',
        'this_month',
    ]
    as $topOperatorMessagersWidget
)

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="box box-widget DashboardWidget expandable">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-primary">
                        <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                            <i class="fa fa-users DashboardWidget_header-icon"></i>
                            <span class="DashboardWidget_header-title">Top operator messagers {{ str_replace('_', ' ', $topOperatorMessagersWidget) }}</span>
                        </div>
                        <!-- /.widget-user-image -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            @php
                                $count = 0;
                            @endphp

                            @foreach($topOperatorMessagerStatistics[$topOperatorMessagersWidget] as $operator)
                                <li class="{{ $count >= $amountOfListItemsVisible ? 'hidden defaultHidden' : '' }}">
                                    <a href="{{ route('admin.peasants.edit.get', ['peasantId' => $operator->getId()]) }}">
                                        {{ $operator->getUsername() }} (ID: {{ $operator->getId() }})
                                        <span class="DashboardWidget_count">{{ $operator->messagesAsOperator->count() }}</span>
                                    </a>
                                </li>

                                @php
                                    $count++;
                                @endphp
                            @endforeach

                            @if(count($topOperatorMessagerStatistics[$topOperatorMessagersWidget]) > $amountOfListItemsVisible)
                                <li>
                                    <a class="showMore" href="#">
                                        Show more <i class="fa fa-chevron-down"></i>
                                    </a>

                                    <a class="showLess hidden" href="#">
                                        Show less <i class="fa fa-chevron-up"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-xs-12"></div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Revenue</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span class="DashboardWidget_count">&euro; {!! $revenueStatistics['revenueToday']/100 !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">&euro; {!! $revenueStatistics['revenueYesterday']/100 !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">&euro; {!! $revenueStatistics['revenueCurrentWeek']/100 !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">&euro; {!! $revenueStatistics['revenueCurrentMonth']/100 !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">&euro; {!! $revenueStatistics['revenuePreviousMonth']/100 !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">&euro; {!! $revenueStatistics['revenueCurrentYear']/100 !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Registrations</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span class="DashboardWidget_count">{!! $registrationStatistics['registrationsToday'] !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">{!! $registrationStatistics['registrationsYesterday'] !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentWeek'] !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentMonth'] !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">{!! $registrationStatistics['registrationsPreviousMonth'] !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentYear'] !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Messages Sent</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span class="DashboardWidget_count">{!! $messageStatistics['messagesSentToday'] !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">{!! $messageStatistics['messagesSentYesterday'] !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">{!! $messageStatistics['messagesSentCurrentWeek'] !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">{!! $messageStatistics['messagesSentCurrentMonth'] !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">{!! $messageStatistics['messagesSentPreviousMonth'] !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">{!! $messageStatistics['messagesSentCurrentYear'] !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-user DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Peasant Messages Sent</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentToday'] !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentYesterday'] !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentWeek'] !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentMonth'] !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentPreviousMonth'] !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentYear'] !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-android DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Bot Messages Sent</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentToday'] !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentYesterday'] !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentCurrentWeek'] !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentCurrentMonth'] !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentPreviousMonth'] !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentCurrentYear'] !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-close DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Deactivations</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsToday'] !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsYesterday'] !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsCurrentWeek'] !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsCurrentMonth'] !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsPreviousMonth'] !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsCurrentYear'] !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-close DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">User types</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">No credits <span class="DashboardWidget_count">{!! $userTypeStatistics['no_credits'] !!}</span></a></li>
                        <li><a href="#">Never bought creditpack <span class="DashboardWidget_count">{!! $userTypeStatistics['never_bought'] !!}</span></a></li>
                        <li><a href="#">Small <span class="DashboardWidget_count">{!! $userTypeStatistics['small'] !!}</span></a></li>
                        <li><a href="#">Medium <span class="DashboardWidget_count">{!! $userTypeStatistics['medium'] !!}</span></a></li>
                        <li><a href="#">Large <span class="DashboardWidget_count">{!! $userTypeStatistics['large'] !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $registrationsChart->container() !!}
                {!! $registrationsChart->script() !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $peasantMessagesChart->container() !!}
                {!! $peasantMessagesChart->script() !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $paymentsChart->container() !!}
                {!! $paymentsChart->script() !!}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $revenueChart->container() !!}
                {!! $revenueChart->script() !!}
            </div>
        </div>
    </div>

@endsection
