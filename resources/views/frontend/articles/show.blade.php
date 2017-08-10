@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <h3> {{ $article->getTitle() }} </h3>
    <div>
        {{ $article->getBody() }}
    </div>
</div>

@endsection
