@extends('frontend.layouts.default.layout')


@section('content')

<div class="row" style="padding-top: 15px">

    @include('frontend.peasants.partials.profile_grid', [
        'users' => $users,
        'carbonNow' => $carbonNow
    ])

    <div class="col-xs-12">
        <div class="pagination__container text-right">
            {!! $users->onEachSide(2)->links() !!}
        </div>
    </div>
</div>

@stop
