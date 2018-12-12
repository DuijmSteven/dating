@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-olive">
                    <div class="widget-us DashboardWidget_header">
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
        <div class="col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-olive">
                    <div class="widget-us DashboardWidget_header">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
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
        <div class="col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-olive">
                    <div class="widget-us DashboardWidget_header">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
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
        <div class="col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-olive">
                    <div class="widget-us DashboardWidget_header">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
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
        <div class="col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-olive">
                    <div class="widget-us DashboardWidget_header">
                        <i class="fa fa-users DashboardWidget_header-icon"></i>
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
    </div>

@endsection
