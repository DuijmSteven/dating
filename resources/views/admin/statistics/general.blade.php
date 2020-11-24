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

{{--        @foreach([--}}
{{--                'this_month',--}}
{{--            ]--}}
{{--            as $topMessagersWidget--}}
{{--        )--}}

{{--            <div class="col-xs-12 col-sm-6 col-md-4">--}}
{{--                <div class="box box-widget DashboardWidget expandable">--}}
{{--                    <!-- Add the bg color to the header using any of the bg-* classes -->--}}
{{--                    <div class="bg-primary">--}}
{{--                        <div class="widget-us DashboardWidget_header"--}}
{{--                             style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">--}}
{{--                            <i class="fa fa-users DashboardWidget_header-icon"></i>--}}
{{--                            <span--}}
{{--                                class="DashboardWidget_header-title">Top messagers {{ str_replace('_', ' ', $topMessagersWidget) }}</span>--}}
{{--                        </div>--}}
{{--                        <!-- /.widget-user-image -->--}}
{{--                    </div>--}}
{{--                    <div class="box-footer no-padding">--}}
{{--                        <ul class="nav nav-stacked">--}}
{{--                            @php--}}
{{--                                $count = 0;--}}
{{--                            @endphp--}}

{{--                            @foreach($topMessagerStatistics[$topMessagersWidget] as $user)--}}
{{--                                <li class="{{ $count >= $amountOfListItemsVisible ? 'hidden defaultHidden' : '' }}">--}}
{{--                                    @php--}}
{{--                                        $highlightTypeClass = '';--}}

{{--                                        if ($user->account->getCredits() > 10) {--}}
{{--                                            $highlightTypeClass = 'success';--}}
{{--                                        } else if ($user->account->credits > 4) {--}}
{{--                                            $highlightTypeClass = 'warning';--}}
{{--                                        } else {--}}
{{--                                            $highlightTypeClass = 'error';--}}
{{--                                        }--}}
{{--                                    @endphp--}}

{{--                                    <a href="{{ route('admin.peasants.edit.get', ['peasantId' => $user->getId()]) }}">--}}
{{--                                        {{ $user->getUsername() }} (ID: {{ $user->getId() }}) - <strong>Credits: <span--}}
{{--                                                class="highlightAsDisk {{ $highlightTypeClass }}">{{ $user->account->getCredits() }}</span></strong>--}}
{{--                                        <span class="DashboardWidget_count">{{ $user->messages->count() }}</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}

{{--                                @php--}}
{{--                                    $count++;--}}
{{--                                @endphp--}}
{{--                            @endforeach--}}

{{--                            @if(count($topMessagerStatistics[$topMessagersWidget]) > $amountOfListItemsVisible)--}}
{{--                                <li>--}}
{{--                                    <a class="showMore" href="#">--}}
{{--                                        Show more <i class="fa fa-chevron-down"></i>--}}
{{--                                    </a>--}}

{{--                                    <a class="showLess hidden" href="#">--}}
{{--                                        Show less <i class="fa fa-chevron-up"></i>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}

{{--        <div class="col-xs-12 col-sm-6 col-md-4">--}}
{{--            <div class="box box-widget DashboardWidget">--}}
{{--                <!-- Add the bg color to the header using any of the bg-* classes -->--}}
{{--                <div class="bg-primary">--}}
{{--                    <div class="widget-us DashboardWidget_header"--}}
{{--                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">--}}
{{--                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>--}}
{{--                        <i class="fa fa-android DashboardWidget_header-icon"></i>--}}
{{--                        <span class="DashboardWidget_header-title">Bot Messages Sent</span>--}}
{{--                    </div>--}}
{{--                    <!-- /.widget-user-image -->--}}
{{--                </div>--}}
{{--                <div class="box-footer no-padding">--}}
{{--                    <ul class="nav nav-stacked">--}}
{{--                        <li><a href="#">Today <span--}}
{{--                                    class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentToday'] !!}</span></a>--}}
{{--                        </li>--}}
{{--                        <li><a href="#">Yesterday <span--}}
{{--                                    class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentYesterday'] !!}</span></a>--}}
{{--                        </li>--}}
{{--                        <li><a href="#">Current week <span--}}
{{--                                    class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentCurrentWeek'] !!}</span></a>--}}
{{--                        </li>--}}
{{--                        <li><a href="#">Current month <span--}}
{{--                                    class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentCurrentMonth'] !!}</span></a>--}}
{{--                        </li>--}}
{{--                        <li><a href="#">Last month <span--}}
{{--                                    class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentPreviousMonth'] !!}</span></a>--}}
{{--                        </li>--}}
{{--                        <li><a href="#">Current year <span--}}
{{--                                    class="DashboardWidget_count">{!! $botMessageStatistics['messagesSentCurrentYear'] !!}</span></a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header"
                         style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                        <i class="fa fa-close DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">User types</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">No credits <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['no_credits'] }}</span></a>
                        </li>
                        <li><a href="#">Never bought creditpack <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['never_bought'] }}</span></a>
                        </li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Small <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['small'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Medium <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['medium'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Large <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['large'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">XL <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Any creditpack <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['small'] + $userTypeStatistics['medium'] + $userTypeStatistics['large'] + $userTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">All time paying users <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['allTimePayingUsersCount'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVPU <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['averageRevenuePerAllTimePayingUser'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVU <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['averageRevenuePerUser'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(4)->format('d-m-Y')]) }}">ALVPU regist. by 4 months ago ( {{ $userTypeStatistics['payingUsersRegisteredUntilFourMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['averageLifetimeValuePerUserRegisteredUntilFourMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(3)->format('d-m-Y')]) }}">ALVPU regist. by 3 months ago ( {{ $userTypeStatistics['payingUsersRegisteredUntilThreeMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['averageLifetimeValuePerUserRegisteredUntilThreeMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(2)->format('d-m-Y')]) }}">ALVPU regist. by 2 months ago ( {{ $userTypeStatistics['payingUsersRegisteredUntilTwoMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['averageLifetimeValuePerUserRegisteredUntilTwoMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(1)->format('d-m-Y')]) }}">ALVPU regist. by 1 month ago ( {{ $userTypeStatistics['payingUsersRegisteredUntilOneMonthAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $userTypeStatistics['averageLifetimeValuePerUserRegisteredUntilOneMonthAgo'] }}</span></a></li>
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
                        <span class="DashboardWidget_header-title">User types Exl. Xpartners</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">No credits <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['no_credits'] }}</span></a>
                        </li>
                        <li><a href="#">Never bought creditpack <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['never_bought'] }}</span></a>
                        </li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Small <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['small'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Medium <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['medium'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Large <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['large'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">XL <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">Any creditpack <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['small'] + $excludingXpartnersUserTypeStatistics['medium'] + $excludingXpartnersUserTypeStatistics['large'] + $excludingXpartnersUserTypeStatistics['xl'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">All time paying users <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['allTimePayingUsersCount'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVPU <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerPayingUserRegistered'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.with-creditpack.overview') }}">ALVU <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegistered'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(5)->format('d-m-Y')]) }}">ALVPU regist. by 5 months ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilFiveMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilFiveMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(4)->format('d-m-Y')]) }}">ALVPU regist. by 4 months ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilFourMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilFourMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(3)->format('d-m-Y')]) }}">ALVPU regist. by 3 months ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilThreeMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilThreeMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(2)->format('d-m-Y')]) }}">ALVPU regist. by 2 months ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilTwoMonthsAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilTwoMonthsAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(45)->format('d-m-Y')]) }}">ALVPU regist. by 1.5 months ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilOneMonthAndAHalfAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilOneMonthAndAHalfAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subMonths(1)->format('d-m-Y')]) }}">ALVPU regist. by 1 month ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilOneMonthAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilOneMonthAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(20)->format('d-m-Y')]) }}">ALVPU regist. by 20 days ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilTwentyDaysAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilTwentyDaysAgo'] }}</span></a></li>
                        <li><a href="{{ route('admin.peasants.created-until-date.show', ['date' => \Carbon\Carbon::now('Europe/Amsterdam')->subDays(10)->format('d-m-Y')]) }}">ALVPU regist. by 10 days ago ( {{ $excludingXpartnersUserTypeStatistics['payingUsersRegisteredUntilTenDaysAgoCount'] }} users) <span
                                    class="DashboardWidget_count">{{ $excludingXpartnersUserTypeStatistics['alvPerUserRegisteredUntilTenDaysAgo'] }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if($averagePeasantMessagesPerHourChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $averagePeasantMessagesPerHourChart->container() !!}
                    {!! $averagePeasantMessagesPerHourChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($peasantMessagesMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $peasantMessagesMonthlyChart->container() !!}
                    {!! $peasantMessagesMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($paymentsChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $paymentsChart->container() !!}
                    {!! $paymentsChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($paymentsMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $paymentsMonthlyChart->container() !!}
                    {!! $paymentsMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($revenueMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $revenueMonthlyChart->container() !!}
                    {!! $revenueMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($revenueWithoutSalesTaxChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $revenueWithoutSalesTaxChart->container() !!}
                    {!! $revenueWithoutSalesTaxChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($revenueWithoutSalesTaxMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $revenueWithoutSalesTaxMonthlyChart->container() !!}
                    {!! $revenueWithoutSalesTaxMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif
@endsection
