@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile">
    <div class="Tile__heading">
        <h4>Artikelen</h4>
    </div>
    <div class="Tile__body Tile__article">
        @foreach ($articles as $article)
            <div class="row">
                <div class="col-md-3 text-center img-responsive">
                    <img src="http://placehold.it/250x250">
                </div>
                <div class="col-md-9">
                    <div>
                        <a href="{{ route('articles.show', ['articleId' => $article->getId()])  }}">{{ strtoupper($article->title) }}</a>
                    </div>
                    <div class="margin10 fontSize15">
                        <i>Posted on {{ $article->created_at->toFormattedDateString() }}</i>
                    </div>
                    <div>
                        {{ str_limit($article->getBody(), 400) }}
                    </div>
                    <div class="text-right margin10">
                        <button type="submit" class="Button Button--primary">
                            <span class="Button__content">Lees Meer</span>
                        </button>
                    </div>
                </div>
            </div>

            <hr>
        @endforeach
    </div>
</div>

@endsection
