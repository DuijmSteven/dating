@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
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

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div style="width: 100%">
                {!! $netPeasantsAcquiredChart->container() !!}
                {!! $netPeasantsAcquiredChart->script() !!}
            </div>
        </div>
    </div>
@endsection
