@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"
                    >
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-user DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">100 Top bot IDs based on messages received</span>
                    </div>
                </div>
                <div class="box-body">
                    @foreach($bestBotsByMessagesReceived as $bot)
                        {{ $bot->getId() }},
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box box-widget DashboardWidget">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="bg-primary">
                    <div class="widget-us DashboardWidget_header" style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"
                    >
                        <i class="fa fa-envelope DashboardWidget_header-icon"></i>
                        <i class="fa fa-user DashboardWidget_header-icon"></i>
                        <span class="DashboardWidget_header-title">100 Top bot IDs based on overall views</span>
                    </div>
                </div>
                <div class="box-body">
                    @foreach($bestBotsByOverallViewsReceived as $bot)
                        {{ $bot->getId() }},
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
