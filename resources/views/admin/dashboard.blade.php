@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">

            <a href="#" class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-aqua">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title"><b>{{ $messageRateLastHour }} peasant messages per hour</b></span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">


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
                        <li><a href="#">Today
                                <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentToday'] !!}
                                    {{--                                ({{ $peasantMessagesPerHourStatistics['today'] }})--}}
                            </span></a>
                        </li>
                        <li><a href="#">Yesterday
                                <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentYesterday'] !!}
                                    {{--                                ({{ $peasantMessagesPerHourStatistics['yesterday'] }})--}}
                            </span></a>
                        </li>
                        <li><a href="#">Current
                                week <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentWeek'] !!}
                                    {{--                                ({{ $peasantMessagesPerHourStatistics['currentWeek'] }})--}}
                            </span></a>
                        </li>
                        <li><a href="#">Current
                                month <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentMonth'] !!}
                                    {{--                                ({{ $peasantMessagesPerHourStatistics['currentMonth'] }})--}}
                            </span></a>
                        </li>
                        <li><a href="#">Last
                                month <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentPreviousMonth'] !!}
                                    {{--                                ({{ $peasantMessagesPerHourStatistics['previousMonth'] }})--}}
                            </span></a>
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
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueToday']/100 }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueToday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueYesterday']/100 }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueYesterday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueCurrentWeek']/100 }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueCurrentWeek']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenueCurrentMonth']/100 }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenueCurrentMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">&euro; {{ $revenueStatistics['revenuePreviousMonth']/100 }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['revenuePreviousMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 7 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($revenueStatistics['averageRevenueLastSevenDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['averageRevenueLastSevenDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 30 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($revenueStatistics['averageRevenueLastThirtyDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($revenueStatistics['averageRevenueLastThirtyDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
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
                        <span class="DashboardWidget_header-title">Conversions</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{{ $conversionStatistics['conversionsToday'] }} </span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{{ $conversionStatistics['conversionsYesterday'] }} </span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{{ $conversionStatistics['conversionsCurrentWeek'] }} </span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{{ $conversionStatistics['conversionsCurrentMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{{ $conversionStatistics['conversionsPreviousMonth'] }} </span></a>
                        </li>
                        <li><a href="#">All time conversion rate <span
                                    class="DashboardWidget_count">{{ number_format($conversionStatistics['allTimeConversionRate'], 1) }}% </span></a>
                        </li>
                        <li><a href="#">Average last 10 days <span
                                    class="DashboardWidget_count">{{ number_format($conversionStatistics['averageLastTenDays'], 1) }} conversions per day </span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
