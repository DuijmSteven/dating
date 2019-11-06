@extends('frontend.layouts.default.layout')

@section('content')

<div class="Tile">
    <div class="Tile__heading">
        <h4>Artikelen</h4>
    </div>
    <div class="Tile__body Tile__articles">
        @foreach ($articles as $article)
            <div class="Article">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="Article__title">
                            <a href="{{ route('articles.show', ['articleId' => $article->getId()])  }}">{{ strtoupper($article->title) }}</a>
                        </div>
                    </div>

                    <div class="col-md-3 text-center img-responsive">
                        <img class="Article__image" src="http://placehold.it/250x250">
                    </div>
                    <div class="col-md-9">
                        <div class="Article__posted">
                            <i class="material-icons fontSize17"> calendar_today </i> <i>Posted on {{ $article->created_at->toFormattedDateString() }}</i>
                        </div>
                        <div class="Article__body">
                            {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(Str::limit($article->getBody(), 400)) !!}
                        </div>
                        <div class="text-right Article__readMore">
                            <a href="{{ route('articles.show', ['article_id' => $article->getId()]) }}" class="Button Button--primary">
                                <span class="Button__content">Lees Meer</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
        @endforeach

        <div class="pagination__container text-right">
            {!! $articles->render() !!}
        </div>
    </div>
</div>

@endsection
