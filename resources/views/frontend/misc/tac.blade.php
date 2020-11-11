@extends('frontend.layouts.default.layout')

@section('content')

    <div class="Tile Tile__privacy">
        <div class="Tile__heading">
            <h4>{{ trans(config('app.directory_name') . '/tac.tac') }} {{ ucfirst(config('app.name')) }}</h4>
        </div>
        <div
                class="Tile__body"
        >
            {!! $tacContent !!}
        </div>
    </div>

@endsection
