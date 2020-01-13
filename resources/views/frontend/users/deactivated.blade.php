@extends('frontend.layouts.default.layout')


@section('content')

<div class="Tile">
    <div class="Tile__heading">
        <h4>{{ trans('deactivation.deactivation') }}</h4>
    </div>
    <div
        class="Tile__body"
    >
        <p>
            {{ trans('deactivation.goodbye_message') }}
        </p>

    </div>
</div>

@stop
