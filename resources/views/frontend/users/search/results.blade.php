@extends('frontend.layouts.default.layout')


@section('content')

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
