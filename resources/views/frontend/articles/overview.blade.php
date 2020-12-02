@extends('frontend.layouts.default.layout')

@section('content')

@foreach ($articles as $article)
    <div class="Tile">
        <div class="Tile__heading">
            <h4>{{ strtoupper($article->title) }}</h4>
        </div>
        <div class="Tile__body Tile__articles">
            <div class="Article">
                <div class="row">
                    @if($article->image_filename)
                        <div class="col-xs-12 text-center img-responsive">
                            <img class="Article__image"
                                 src="{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename, false) !!}"
                                 alt="Article image">
                        </div>
                    @endif

                    <div class="col-xs-12">

                        <div class="Article__body">
                            {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(Str::limit($article->getBody(), 400)) !!}
                        </div>
                        <div class="text-right Article__readMore">
                            <a href="{{ route('articles.show', ['slug' => $article->getSlug()]) }}"
                               class="Button Button--primary">
                                <span
                                    class="Button__content">{{ trans(config('app.directory_name') . '/articles.read_more') }}</span>
                            </a>
                        </div>

                        <div class="Article__posted">
                            <i class="material-icons fontSize17"> calendar_today </i>
                            <i>{{ trans(config('app.directory_name') . '/articles.posted_on') }} {{ $article->created_at->toFormattedDateString() }}</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="pagination__container text-right">
    {!! $articles->render() !!}
</div>

@endsection
