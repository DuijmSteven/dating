@extends('frontend.layouts.default.layout')

@section('content')

    @foreach($activity as $activityItem)

        @include('frontend.components.activity', [
            'activityThumbnailUrl' => $activityItem->getThumbnailUrl(),
            'activityTitle' => $activityItem->getTitle(),
            'activityDate' => $activityItem->getDateFormatted(),
            'activityImageUrl' => $activityItem->getImageUrl(),
            'activityText' => $activityItem->getText(),
        ])
    @endforeach

@endsection
