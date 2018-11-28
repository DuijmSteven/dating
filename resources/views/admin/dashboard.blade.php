@extends('admin.layouts.default.layout')

@section('content')

    <div class="col-sm-6 col-md-4">
        <div class="row">
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
                        <li><a href="#">Today <span class="DashboardWidget_count">{!! $registrationsToday !!}</span></a></li>
                        <li><a href="#">Yesterday <span class="DashboardWidget_count">{!! $registrationsYesterday !!}</span></a></li>
                        <li><a href="#">Current week <span class="DashboardWidget_count">{!! $registrationsCurrentWeek !!}</span></a></li>
                        <li><a href="#">Current month <span class="DashboardWidget_count">{!! $registrationsCurrentMonth !!}</span></a></li>
                        <li><a href="#">Last month <span class="DashboardWidget_count">{!! $registrationsPreviousMonth !!}</span></a></li>
                        <li><a href="#">Current year <span class="DashboardWidget_count">{!! $registrationsCurrentYear !!}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
