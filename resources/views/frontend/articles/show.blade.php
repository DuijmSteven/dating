@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile Tile__article">
    <div class="Tile__heading">
        <h4>{!! $article->getTitle() !!}</h4>
    </div>
    <div
        data-provide="markdown"
        class="Tile__body"
    >
        {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($article->getBody()) !!}
    </div>
</div>

@endsection
