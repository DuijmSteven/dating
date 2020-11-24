@extends('admin.layouts.default.layout')

@section('content')

    <div class="row">
        @php
            $amountOfListItemsVisible = 5;
        @endphp

        @foreach([
                'today',
                'this_week',
                'this_month',
            ]
            as $topOperatorMessagersWidget
        )

            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="box box-widget DashboardWidget expandable">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="bg-primary">
                        <div class="widget-us DashboardWidget_header"
                             style="white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                            <i class="fa fa-users DashboardWidget_header-icon"></i>
                            <span
                                class="DashboardWidget_header-title">Top operator messagers {{ str_replace('_', ' ', $topOperatorMessagersWidget) }}</span>
                        </div>
                        <!-- /.widget-user-image -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            @php
                                $count = 0;
                            @endphp

                            @foreach($topOperatorMessagerStatistics[$topOperatorMessagersWidget] as $operator)
                                <li class="{{ $count >= $amountOfListItemsVisible ? 'hidden defaultHidden' : '' }}">
                                    <a href="{{ route('admin.operators.edit.get', ['operatorId' => $operator->getId()]) }}">
                                        {{ $operator->getUsername() }} (ID: {{ $operator->getId() }})
                                        <span
                                            class="DashboardWidget_count">{{ $operator->messagesAsOperator->count() }}</span>
                                    </a>
                                </li>

                                @php
                                    $count++;
                                @endphp
                            @endforeach

                            @if(count($topOperatorMessagerStatistics[$topOperatorMessagersWidget]) > $amountOfListItemsVisible)
                                <li>
                                    <a class="showMore" href="#">
                                        Show more <i class="fa fa-chevron-down"></i>
                                    </a>

                                    <a class="showLess hidden" href="#">
                                        Show less <i class="fa fa-chevron-up"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
