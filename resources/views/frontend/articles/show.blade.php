@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <a href="{{ url()->previous() }}" class="GeneralBackButton">
            <i class="material-icons">
                chevron_left
            </i>{{ trans(config('app.directory_name') . '/user_profile.back') }}
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
                "name": "Datevrij Author",
                "worksFor": {
                    "@type": "Organization",
                    "name": "Datevrij"
                }
            },
            "publisher": {
                "@type": "Organization",
                "name": "Datevrij",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{!! asset('img/site_logos/' . config('app.directory_name') . '/Datevrij_LogoSmall_Pos@1x.png') !!}"
                },
                "url": "https://datevrij.nl/"
            },
            "description": "{!! Str::limit($article->getBody(), 400) !!}"
        }
</script>

@endsection
