@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <a href="{{ url()->previous() }}" class="GeneralBackButton">
            <i class="material-icons">
                chevron_left
            </i>{{ trans('user_profile.back') }}
        </a>
    </div>
</div>

<div class="Tile Tile__article">
    <div class="Tile__heading">
        <h4>{!! $article->getTitle() !!}</h4>
    </div>
    <div
        data-provide="markdown"
        class="Tile__body"
    >
        @if($article->image_filename)
            <div class="img-responsive">
                <img class="Article__image"
                     src="{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename, false) !!}"
                     alt="{!! $article->getTitle() !!}">
            </div>
        @endif

        {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($article->getBody()) !!}
    </div>
</div>

<script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BlogPosting",
            "mainEntityOfPage": {
                "@type": "WebPage"
            },
            "headline": "{!! $article->getTitle() !!}",
            "image": "{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename, false) !!}",
            "datePublished": "{!! $article->created_at->toFormattedDateString() !!}",
            "dateModified": "{!! $article->updated_at->toFormattedDateString() !!}",
            "author": {
                "@type": "Person",
                "name": "Altijdsex Author",
                "worksFor": {
                    "@type": "Organization",
                    "name": "Altijdsex"
                }
            },
            "publisher": {
                "@type": "Organization",
                "name": "Altijdsex",
                "logo": {
                    "@type": "ImageObject",
                    "url": "https://altijdsex.nl/img/site_logos/Altijdsex_LogoBig_Pos@1x.png"
                },
                "url": "https://altijdsex.nl/"
            },
            "description": "{!! Str::limit($article->getBody(), 400) !!}"
        }
</script>

@endsection
