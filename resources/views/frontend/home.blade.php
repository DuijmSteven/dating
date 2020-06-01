@extends('frontend.layouts.default.layout')

@section('content')

    <div class="Tile Activity">
        <div class="Tile__heading" style="padding: 5px">
            <span class="material-icons">
                chat
            </span>
            {{ trans('home.public_chat') }}
        </div>
    </div>

    @include('frontend.components.public-chat')

    <div class="Tile Activity">
        <div class="Tile__heading" style="padding: 5px">
            <span class="material-icons">
                account_box
            </span>
            {{ trans('home.new_members') }}
        </div>
    </div>


    @foreach($users as $user)

        @include('frontend.components.user-summary', ['user' => $user, 'showAboutMe' => true])

{{--        @include('frontend.components.activity', [--}}
{{--            'user' => $user,--}}
{{--            'activityThumbnailUrl' => \StorageHelper::profileImageUrl($user, true),--}}
{{--            'activityTitle' => $user->getUsername(),--}}
{{--            'activityImageUrl' => \StorageHelper::profileImageUrl($user),--}}
{{--            'activityText' => $user->meta->getAboutMe(),--}}
{{--        ])--}}
    @endforeach

@endsection
