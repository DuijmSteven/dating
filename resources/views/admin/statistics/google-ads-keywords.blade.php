@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="box box-success collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body" style="">
                    <form method="POST" action="{{ route('admin.statistics.google-ads.search') }}">
                        {{ csrf_field() }}

                        <h4>Created at between</h4>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>After:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text"
                                               class="form-control pull-right datepicker__date defaultToPresent"
                                               name="created_at_after"
                                               value="{{ old('created_at_after') ? old('created_at_after') : '' }}"
                                        >
                                        @if ($errors->has('created_at_after'))
                                            {!! $errors->first('created_at_after', '<small class="form-error">:message</small>') !!}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Before:</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text"
                                               class="form-control pull-right datepicker__date defaultToPresent"
                                               name="created_at_before"
                                               value="{{ old('created_at_before') ? old('created_at_before') : '' }}"
                                        >
                                        @if ($errors->has('created_at_before'))
                                            {!! $errors->first('created_at_before', '<small class="form-error">:message</small>') !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 text-right">
                                @include('frontend.components.button', [
                                     'buttonContext' => 'form',
                                     'buttonType' => 'submit',
                                     'buttonState' => 'primary',
                                     'buttonText' => 'SEARCH'
                                 ])
                            </div>
                        </div>
                    </form>
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
                        <span class="DashboardWidget_header-title">Leads per keyword</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        @foreach($leadsPerKeyword as $item)
                            <li>
                                <a href="#">
                                    {{ $item->keyword }}
                                    <span class="DashboardWidget_count">
                                        {{ $item->count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
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
                        <span class="DashboardWidget_header-title">Conversions per keyword</span>
                    </div>
                    <!-- /.widget-user-image -->
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        @foreach($conversionsPerKeyword as $item)
                            <li>
                                <a href="#">
                                    {{ $item->keyword }}
                                    <span class="DashboardWidget_count">
                                        {{ $item->count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
