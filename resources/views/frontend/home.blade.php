@extends('frontend.layouts.default.layout')

@section('content')

    @foreach($users as $user)

        @include('frontend.components.activity', [
            'user' => $user,
            'activityThumbnailUrl' => \StorageHelper::profileImageUrl($user, true),
            'activityTitle' => $user->getUsername(),
            'activityImageUrl' => \StorageHelper::profileImageUrl($user),
            'activityText' => $user->meta->getAboutMe(),
        ])
    @endforeach

@endsection
