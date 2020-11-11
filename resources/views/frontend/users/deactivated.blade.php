@extends('frontend.layouts.default.layout')


@section('content')

<div class="Tile">
    <div class="Tile__heading">
        <h4>{{ trans(config('app.directory_name') . '/deactivation.deactivation') }}</h4>
    </div>
    <div
        class="Tile__body"
    >
        <p>
            {{ trans(config('app.directory_name') . '/deactivation.goodbye_message') }}
        </p>

    </div>
</div>

@stop
