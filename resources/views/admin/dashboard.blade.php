@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="row" style="margin-bottom: 20px">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
{{--                    <a href="#" class="box box-widget DashboardWidget">--}}
{{--                        <!-- Add the bg color to the header using any of the bg-* classes -->--}}
{{--                        <div class="bg-info">--}}
{{--                            <div class="widget-us DashboardWidget_header"--}}
{{--                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">--}}
{{--                                <i class="fa fa-users DashboardWidget_header-icon"></i>--}}
{{--                                <span class="DashboardWidget_header-title"><b>{{ $peasantMessagesSentToday }} peasant messages today</b></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
                    <a href="{{ route('admin.bots.online.show') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-info">
                            <div class="widget-us DashboardWidget_header"
                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $onlineFemaleStraightBotsCount }} female straight bots online</b></span>
                            </div>
                        </div>
                    </a>
{{--                    <a href="{{ route('admin.bots.online.show') }}" class="box box-widget DashboardWidget">--}}
{{--                        <!-- Add the bg color to the header using any of the bg-* classes -->--}}
{{--                        <div class="bg-info">--}}
{{--                            <div class="widget-us DashboardWidget_header"--}}
{{--                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">--}}
{{--                                <i class="fa fa-users DashboardWidget_header-icon"></i>--}}
{{--                                <span class="DashboardWidget_header-title"><b>{{ $onlineMaleStraightBotsCount }} male straight bots online</b></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
                    <a href="{{ route('admin.bots.retrieve') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-info">
                            <div class="widget-us DashboardWidget_header"
                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $activeFemaleStraightBotsCount }} active female straight bots</b></span>
                            </div>
                        </div>
                    </a>

{{--                    <a href="{{ route('admin.bots.retrieve') }}" class="box box-widget DashboardWidget">--}}
{{--                        <!-- Add the bg color to the header using any of the bg-* classes -->--}}
{{--                        <div class="bg-info">--}}
{{--                            <div class="widget-us DashboardWidget_header"--}}
{{--                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">--}}
{{--                                <i class="fa fa-users DashboardWidget_header-icon"></i>--}}
{{--                                <span class="DashboardWidget_header-title"><b>{{ $activeMaleStraightBotsCount }} active male straight bots</b></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
                    <a href="{{ route('admin.peasants.online.show') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-pinkish">
                            <div class="widget-us DashboardWidget_header"
                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $onlinePeasantsCount }} peasants online</b></span>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.peasants.online.show') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-aqua">
                            <div class="widget-us DashboardWidget_header"
                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $availableConversationsCount }} available conversations</b></span>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.peasants.online.show') }}" class="box box-widget DashboardWidget">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="bg-aqua">
                            <div class="widget-us DashboardWidget_header"
                                 style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                <i class="fa fa-users DashboardWidget_header-icon"></i>
                                <span class="DashboardWidget_header-title"><b>{{ $stoppedConversationsCount }} stopped conversations</b></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-user DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Peasant Messages Sent (+ per hour)</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentToday'] !!} ({{ $peasantMessagesPerHourStatistics['today'] }})</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentYesterday'] !!} ({{ $peasantMessagesPerHourStatistics['yesterday'] }})</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentWeek'] !!} ({{ $peasantMessagesPerHourStatistics['currentWeek'] }})</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentMonth'] !!} ({{ $peasantMessagesPerHourStatistics['currentMonth'] }})</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentPreviousMonth'] !!} ({{ $peasantMessagesPerHourStatistics['previousMonth'] }})</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentYear'] !!} ({{ $peasantMessagesPerHourStatistics['currentYear'] }})</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Revenue (without sales tax in parentheses)</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueToday']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueToday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueYesterday']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueYesterday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueCurrentWeek']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueCurrentWeek']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueCurrentMonth']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueCurrentMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenuePreviousMonth']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenuePreviousMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueCurrentYear']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueCurrentYear']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">X-Partners Revenue (without sales tax in parentheses)</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['revenueToday']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueToday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['revenueYesterday']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueYesterday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['revenueCurrentWeek']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueCurrentWeek']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['revenueCurrentMonth']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueCurrentMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['revenuePreviousMonth']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenuePreviousMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['revenueCurrentYear']/100 }} (<span style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueCurrentYear']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-close DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Net peasants acquired</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsToday'] - $peasantDeactivationStatistics['deactivationsToday'] !!}</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsYesterday'] - $peasantDeactivationStatistics['deactivationsYesterday'] !!}</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentWeek'] - $peasantDeactivationStatistics['deactivationsCurrentWeek'] !!}</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentMonth'] - $peasantDeactivationStatistics['deactivationsCurrentMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsPreviousMonth'] - $peasantDeactivationStatistics['deactivationsPreviousMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentYear'] - $peasantDeactivationStatistics['deactivationsCurrentYear'] !!}</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-user DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Public Chat Peasant Messages Sent</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{!! $peasantPublicChatMessageStatistics['messagesSentToday'] !!}</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{!! $peasantPublicChatMessageStatistics['messagesSentYesterday'] !!}</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{!! $peasantPublicChatMessageStatistics['messagesSentCurrentWeek'] !!}</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{!! $peasantPublicChatMessageStatistics['messagesSentCurrentMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{!! $peasantPublicChatMessageStatistics['messagesSentPreviousMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{!! $peasantPublicChatMessageStatistics['messagesSentCurrentYear'] !!}</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @php
            $amountOfListItemsVisible = 5;
        @endphp

        @foreach([
                'today',
                'this_week',
            ]
            as $topMessagersWidget
        )

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="box box-widget DashboardWidget expandable">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-primary">
                        <div class="widget-us DashboardWidget_header"
                             style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                            <i class="fa fa-users DashboardWidget_header-icon"></i>
                            <span
                                class="DashboardWidget_header-title">Top messagers {{ str_replace('_', ' ', $topMessagersWidget) }}</span>
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
                                        {{ $user->getUsername() }} (ID: {{ $user->getId() }}) - <strong>Credits: <span
                                                class="highlightAsDisk {{ $highlightTypeClass }}">{{ $user->account->getCredits() }}</span></strong>
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
                'last_ten_minutes',
                'last_hour',
            ]
            as $messagersOnARollWidget
        )

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="box box-widget DashboardWidget expandable">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-primary">
                        <div class="widget-us DashboardWidget_header"
                             style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                            <i class="fa fa-users DashboardWidget_header-icon"></i>
                            <span
                                class="DashboardWidget_header-title"
                            >
                                Messagers on a roll {{ str_replace('_', ' ', $messagersOnARollWidget) }} (At least {{ $messagersOnARollStatistics[$messagersOnARollWidget]['countLimit'] }} messages sent)
                            </span>
                        </div>
                        <!-- /.widget-user-image -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            @php
                                $count = 0;
                            @endphp

                            @foreach($messagersOnARollStatistics[$messagersOnARollWidget]['peasants'] as $user)
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
                                        {{ $user->getUsername() }} (ID: {{ $user->getId() }}) - <strong>Credits: <span
                                                class="highlightAsDisk {{ $highlightTypeClass }}">{{ $user->account->getCredits() }}</span></strong>
                                        <span class="DashboardWidget_count">{{ $user->messages->count() }}</span>
                                    </a>
                                </li>

                                @php
                                    $count++;
                                @endphp
                            @endforeach

                            @if(count($messagersOnARollStatistics[$messagersOnARollWidget]) > $amountOfListItemsVisible)
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
            ]
            as $topOperatorMessagersWidget
        )

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="box box-widget DashboardWidget expandable">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-primary">
                        <div class="widget-us DashboardWidget_header"
                             style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                            <i class="fa fa-users DashboardWidget_header-icon"></i>
                            <span
                                class="DashboardWidget_header-title">Top operator messagers {{ str_replace('_', ' ', $topOperatorMessagersWidget) }}</span>
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
                                    <a href="{{ route('admin.operators.edit.get', ['peasantId' => $operator->getId()]) }}">
                                        {{ $operator->getUsername() }} (ID: {{ $operator->getId() }})
                                        <span
                                            class="DashboardWidget_count">{{ $operator->messagesAsOperator->count() }}</span>
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

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $netPeasantsAcquiredChart->container() !!}
                {!! $netPeasantsAcquiredChart->script() !!}
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
                {!! $revenueChart->container() !!}
                {!! $revenueChart->script() !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $xpartnersRevenueChart->container() !!}
                {!! $xpartnersRevenueChart->script() !!}
            </div>
        </div>
    </div>
@endsection
