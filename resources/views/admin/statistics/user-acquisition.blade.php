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
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">Registrations</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsToday'] !!}</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsYesterday'] !!}</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentWeek'] !!}</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsPreviousMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{!! $registrationStatistics['registrationsCurrentYear'] !!}</span></a>
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
                        <span class="DashboardWidget_header-title">Deactivations</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Today <span
                                    class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsToday'] !!}</span></a>
                        </li>
                        <li><a href="#">Yesterday <span
                                    class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsYesterday'] !!}</span></a>
                        </li>
                        <li><a href="#">Current week <span
                                    class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsCurrentWeek'] !!}</span></a>
                        </li>
                        <li><a href="#">Current month <span
                                    class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsCurrentMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Last month <span
                                    class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsPreviousMonth'] !!}</span></a>
                        </li>
                        <li><a href="#">Current year <span
                                    class="DashboardWidget_count">{!! $peasantDeactivationStatistics['deactivationsCurrentYear'] !!}</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    @if($registrationsChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $registrationsChart->container() !!}
                    {!! $registrationsChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($deactivationsChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $deactivationsChart->container() !!}
                    {!! $deactivationsChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($registrationsMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $registrationsMonthlyChart->container() !!}
                    {!! $registrationsMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($deactivationsMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $deactivationsMonthlyChart->container() !!}
                    {!! $deactivationsMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif
    @if($netPeasantsAcquiredMonthlyChart)
        <div class="row">
            <div class="col-xs-12">
                <div style="width: 100%">
                    {!! $netPeasantsAcquiredMonthlyChart->container() !!}
                    {!! $netPeasantsAcquiredMonthlyChart->script() !!}
                </div>
            </div>
        </div>
    @endif

@endsection
