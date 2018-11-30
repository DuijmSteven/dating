@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile">
    <div class="Tile__heading">
        Artikelen
    </div>
    <div class="Tile__body">
        @foreach ($articles as $article)
            <div>
                <a href="{{ route('articles.show', ['articleId' => $article->getId()])  }}">{{ $article->title }}</a>
            </div>
        @endforeach
    </div>
</div>

@endsection
