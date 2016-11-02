@extends('frontend.layouts.default.layout')


@section('content')

<div class="row">
    @include('frontend.users.partials.profile_grid', [
        'users' => $users,
        'carbonNow' => $carbonNow
    ])
</div>

@stop
