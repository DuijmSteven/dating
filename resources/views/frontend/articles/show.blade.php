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
                <img class="Article__image" src="{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename, false) !!}" alt="Article image">
            </div>
        @endif

        {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($article->getBody()) !!}
    </div>
</div>

@endsection
