@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile">
    <div class="Tile__heading">
        {!! $article->getTitle() !!}
    </div>
    <div class="Tile__body">
        {{ $article->getBody() }}
    </div>
</div>

@endsection
