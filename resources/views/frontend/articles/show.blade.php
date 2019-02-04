@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile">
    <div class="Tile__heading">
        <h4>{!! $article->getTitle() !!}</h4>
    </div>
    <div class="Tile__body Tile__article">
        {{ $article->getBody() }}
    </div>
</div>

@endsection
