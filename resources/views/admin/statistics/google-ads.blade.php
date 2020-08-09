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
                            class="DashboardWidget_header-title">Google Ads Revenue (without sales tax in parentheses)</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['revenueToday']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['revenueToday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['revenueYesterday']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['revenueYesterday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['revenueCurrentWeek']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['revenueCurrentWeek']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['revenueCurrentMonth']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['revenueCurrentMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['revenuePreviousMonth']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['revenuePreviousMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['revenueCurrentYear']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['revenueCurrentYear']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 7 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['averageRevenueLastSevenDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['averageRevenueLastSevenDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 30 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsRevenueStatistics['averageRevenueLastThirtyDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsRevenueStatistics['averageRevenueLastThirtyDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">All time ad expenses <span
                                    class="DashboardWidget_count">&euro; {{ $googleAdsRevenueStatistics['allTimeAdExpenses']/100 }} </span></a>
                        </li>
                        <li><a href="#">All time net revenue <span
                                    class="DashboardWidget_count">&euro; {{ $googleAdsRevenueStatistics['allTimeNetRevenue']/100 }} </span></a>
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
                            class="DashboardWidget_header-title">Google Ads Revenue Belgium (without sales tax in parentheses)</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['revenueToday']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['revenueToday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['revenueYesterday']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['revenueYesterday']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['revenueCurrentWeek']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['revenueCurrentWeek']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['revenueCurrentMonth']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['revenueCurrentMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['revenuePreviousMonth']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['revenuePreviousMonth']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['revenueCurrentYear']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['revenueCurrentYear']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 7 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['averageRevenueLastSevenDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['averageRevenueLastSevenDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
                        </li>
                        <li><a href="#">Average last 30 days <span
                                    class="DashboardWidget_count">&euro; {{ number_format($googleAdsBelgiumRevenueStatistics['averageRevenueLastThirtyDays']/100, 2) }} (<span
                                        style="color: #08a05e; font-weight: 600">{{ number_format($googleAdsBelgiumRevenueStatistics['averageRevenueLastThirtyDays']/(1 + $salesTax)/100, 2) }}</span>)</span></a>
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
                        <span class="DashboardWidget_header-title">Google Ads Conversions</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{{ $googleAdsConversionStatistics['conversionsToday'] }} </span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{{ $googleAdsConversionStatistics['conversionsYesterday'] }} </span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{{ $googleAdsConversionStatistics['conversionsCurrentWeek'] }} </span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{{ $googleAdsConversionStatistics['conversionsCurrentMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{{ $googleAdsConversionStatistics['conversionsPreviousMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{{ $googleAdsConversionStatistics['conversionsCurrentYear'] }} </span></a>
                        </li>
                        <li><a href="#">All time conversion rate <span
                                    class="DashboardWidget_count">{{ number_format($googleAdsConversionStatistics['allTimeConversionRate'], 1) }}% </span></a>
                        </li>
                        <li><a href="#">All time cost per conversion <span
                                    class="DashboardWidget_count">&euro;{{ number_format($googleAdsConversionStatistics['allTimeCostPerConversion'] / 100, 2) }} </span></a>
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
                        <span class="DashboardWidget_header-title">Google Ads Belgium Conversions</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{{ $googleAdsBelgiumConversionStatistics['conversionsToday'] }} </span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{{ $googleAdsBelgiumConversionStatistics['conversionsYesterday'] }} </span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{{ $googleAdsBelgiumConversionStatistics['conversionsCurrentWeek'] }} </span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{{ $googleAdsBelgiumConversionStatistics['conversionsCurrentMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{{ $googleAdsBelgiumConversionStatistics['conversionsPreviousMonth'] }} </span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{{ $googleAdsBelgiumConversionStatistics['conversionsCurrentYear'] }} </span></a>
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
                        <span class="DashboardWidget_header-title">User types Google ads</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">No credits <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['no_credits'] }}</span></a>
                        </li>
                        <li><a href="#">Never bought creditpack <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['never_bought'] }}</span></a>
                        </li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Small <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['small'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Medium <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['medium'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Large <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['large'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">XL <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Any creditpack <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['small'] + $googleAdsUserTypeStatistics['medium'] + $googleAdsUserTypeStatistics['large'] + $googleAdsUserTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">All time paying users <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['allTimePayingUsersCount'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVPU <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerPayingUserRegistered'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVU <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegistered'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(4)->format('d-m-Y')]) }}">ALVPU regist. by 4 months ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilFourMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilFourMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(3)->format('d-m-Y')]) }}">ALVPU regist. by 3 months ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilThreeMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilThreeMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(2)->format('d-m-Y')]) }}">ALVPU regist. by 2 months ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilTwoMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilTwoMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(45)->format('d-m-Y')]) }}">ALVPU regist. by 1.5 months ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilOneMonthAndAHalfAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilOneMonthAndAHalfAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(1)->format('d-m-Y')]) }}">ALVPU regist. by 1 month ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilOneMonthAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilOneMonthAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(20)->format('d-m-Y')]) }}">ALVPU regist. by 20 days ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilTwentyDaysAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilTwentyDaysAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(10)->format('d-m-Y')]) }}">ALVPU regist. by 10 days ago ( {{ $googleAdsUserTypeStatistics['payingUsersRegisteredUntilTenDaysAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $googleAdsUserTypeStatistics['alvPerUserRegisteredUntilTenDaysAgo'] }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $googleAdsPeasantMessagesChart->container() !!}
                {!! $googleAdsPeasantMessagesChart->script() !!}
            </div>
        </div>
    </div>

    @if($googleAdsRevenueChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $googleAdsRevenueChart->container() !!}
                    {!! $googleAdsRevenueChart->script() !!}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $googleLeadsChart->container() !!}
                {!! $googleLeadsChart->script() !!}
            </div>
        </div>
    </div>

@endsection
