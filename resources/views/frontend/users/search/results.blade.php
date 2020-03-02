@extends('frontend.layouts.default.layout')


@section('content')

<div class="row JS--searchResultsHeaderRow">
    <div class="col-xs-12">
        <h3 class="searchResultsHeader">{!! trans('user_search.search_results_heading', ['city' => $city, 'radius' => $radius]) !!}</h3>
    </div>
</div>

<div class="row">
    @include('frontend.peasants.partials.profile_grid', [
        'users' => $users,
        'carbonNow' => $carbonNow
    ])

    <div class="col-xs-12">
        <div class="pagination__container text-right">
            {!! $users->render() !!}
        </div>
    </div>
</div>

@stop
