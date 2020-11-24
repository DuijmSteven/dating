@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        @php
            $amountOfListItemsVisible = 5;
        @endphp

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-user DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Peasant Messages Sent</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentToday'] !!}</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentYesterday'] !!}</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentWeek'] !!}</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentPreviousMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{!! $peasantMessageStatistics['messagesSentCurrentYear'] !!}</span></a>
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
                        <span
                            class="DashboardWidget_header-title">Xpartners Revenue (without sales tax in parentheses)</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['revenueToday']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueToday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['revenueYesterday']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueYesterday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['revenueCurrentWeek']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueCurrentWeek']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['revenueCurrentMonth']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueCurrentMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['revenuePreviousMonth']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenuePreviousMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['revenueCurrentYear']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['revenueCurrentYear']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 7 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['averageRevenueLastSevenDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['averageRevenueLastSevenDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 30 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($xpartnersRevenueStatistics['averageRevenueLastThirtyDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($xpartnersRevenueStatistics['averageRevenueLastThirtyDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">All time ad expenses <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['allTimeAdExpenses']/100 }} </span></a>
                        </li>
                        <li><a href="#">All time net revenue <span
                                    class="DashboardWidget_count">&euro; {{ $xpartnersRevenueStatistics['allTimeNetRevenue']/100 }} </span></a>
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
                        <span class="DashboardWidget_header-title">Xpartners Conversions</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{{ $xpartnersConversionStatistics['conversionsToday'] }} </span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{{ $xpartnersConversionStatistics['conversionsYesterday'] }} </span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{{ $xpartnersConversionStatistics['conversionsCurrentWeek'] }} </span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{{ $xpartnersConversionStatistics['conversionsCurrentMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{{ $xpartnersConversionStatistics['conversionsPreviousMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{{ $xpartnersConversionStatistics['conversionsCurrentYear'] }} </span></a>
                        </li>
                        <li><a href="#">All time conversion rate <span
                                    class="DashboardWidget_count">{{ number_format($xpartnersConversionStatistics['allTimeConversionRate'], 1) }}% </span></a>
                        </li>
                        <li><a href="#">All time cost per conversion <span
                                    class="DashboardWidget_count">&euro;{{ number_format($xpartnersConversionStatistics['allTimeCostPerConversion'] / 100, 2) }} </span></a>
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
                        <span class="DashboardWidget_header-title">User types Xpartners</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">No credits <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['no_credits'] }}</span></a>
                        </li>
                        <li><a href="#">Never bought creditpack <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['never_bought'] }}</span></a>
                        </li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Small <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['small'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Medium <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['medium'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Large <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['large'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">XL <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Any creditpack <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['small'] + $xpartnersUserTypeStatistics['medium'] + $xpartnersUserTypeStatistics['large'] + $xpartnersUserTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">All time paying users <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['allTimePayingUsersCount'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVPU <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerPayingUserRegistered'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVU <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegistered'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(4)->format('d-m-Y')]) }}">ALVPU regist. by 4 months ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilFourMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilFourMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(3)->format('d-m-Y')]) }}">ALVPU regist. by 3 months ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilThreeMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilThreeMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(2)->format('d-m-Y')]) }}">ALVPU regist. by 2 months ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilTwoMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilTwoMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(45)->format('d-m-Y')]) }}">ALVPU regist. by 1.5 months ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilOneMonthAndAHalfAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilOneMonthAndAHalfAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(1)->format('d-m-Y')]) }}">ALVPU regist. by 1 month ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilOneMonthAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilOneMonthAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(20)->format('d-m-Y')]) }}">ALVPU regist. by 20 days ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilTwentyDaysAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilTwentyDaysAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(10)->format('d-m-Y')]) }}">ALVPU regist. by 10 days ago ( {{ $xpartnersUserTypeStatistics['payingUsersRegisteredUntilTenDaysAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $xpartnersUserTypeStatistics['alvPerUserRegisteredUntilTenDaysAgo'] }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $xpartnersPeasantMessagesChart->container() !!}
                {!! $xpartnersPeasantMessagesChart->script() !!}
            </div>
        </div>
    </div>

    @if($xpartnersRevenueChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $xpartnersRevenueChart->container() !!}
                    {!! $xpartnersRevenueChart->script() !!}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $xpartnersConversionsChart->container() !!}
                {!! $xpartnersConversionsChart->script() !!}
            </div>
        </div>
    </div>

@endsection
