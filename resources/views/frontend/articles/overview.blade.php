@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    @foreach ($articles as $article)
        <div>
            <a href="{{ route('articles.show', ['articleId' => $article->getId()])  }}">{{ $article->title }}</a>
        </div>
    @endforeach
</div>

@endsection
