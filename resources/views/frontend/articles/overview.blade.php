@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile">
    <div class="Tile__heading">
        <h4>Artikelen</h4>
    </div>
    <div class="Tile__body Tile__article">
        @foreach ($articles as $article)
            <div>
                <a href="{{ route('articles.show', ['articleId' => $article->getId()])  }}">{{ $article->title }}</a>
            </div>
            <div>
                {{ $article->getMetaDescription() }}
            </div>
            <hr>
        @endforeach
    </div>
</div>

@endsection
