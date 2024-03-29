@extends('frontend.layouts.default.layout')


@section('content')

    <div class="Tile EditProfile JS--Edit-Profile">
        <div class="Tile__heading">
            <span class="material-icons">
                build_circle
            </span>
            {{ trans(config('app.directory_name') . '/edit_profile.edit_profile') }}
        </div>
        <div class="Tile__body">

            <form role="form" method="POST"
                  action="{!! route('users.update', ['userId' => $user->id]) !!}"
                  enctype="multipart/form-data"
                  id="JS--EditProfileUserDetailsForm"
            >
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="username">{{ trans(config('app.directory_name') . '/user_constants.username') }}</label>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       disabled
                                       value="{!! $user->username !!}"
                                >
                                @if ($errors->has('username'))
                                    {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="username">{{ trans(config('app.directory_name') . '/user_constants.email') }}</label>
                                <input type="text"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       value="{!! $user->email !!}"
                                >
                                @if ($errors->has('email'))
                                    {!! $errors->first('email', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="city">{{ trans(config('app.directory_name') . '/user_constants.city') }}</label>
                                <input type="text"
                                       class="JS--autoCompleteCites JS--edit form-control"
                                       name="city"
                                       value="{!! ucfirst($user->meta->city) !!}"
                                >
                                @if ($errors->has('city'))
                                    {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="dob">{{ trans(config('app.directory_name') . '/user_constants.dob') }}</label>
                                <div class="input-group date dateOfBirthGroup">
                                    <input id="datepicker_dob"
                                           type="text"
                                           class="form-control"
                                           name="dob"
                                           required
                                           value="{{ $user->meta->dob ? $user->meta->dob->format('d-m-Y') : '' }}"
                                    >
                                    <div class="input-group-addon" style="padding: 3px 12px">
                                        <i class="material-icons calendar">
                                            calendar_today
                                        </i>
                                    </div>
                                </div>
                                @if ($errors->has('dob'))
                                    @if ($errors->has('dob'))
                                        {!! $errors->first('dob', '<small class="form-error">:message</small>') !!}
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="relationship_status">{{ trans(config('app.directory_name') . '/user_constants.labels.relationship_status') }}</label>
                                <select name="relationship_status"
                                        id="relationship_status"
                                        class="form-control"
                                >
                                    @if(!$user->meta['relationship_status'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.relationship_status')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('relationship_status') as $key => $value)
                                        <option
                                            {{ $user->meta['relationship_status'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.relationship_status.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="body_type">{{ trans(config('app.directory_name') . '/user_constants.labels.body_type') }}</label>
                                <select name="body_type"
                                        id="body_type"
                                        class="form-control"
                                >
                                    @if(!$user->meta['body_type'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.body_type')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('body_type') as $key => $value)
                                        <option
                                            {{ $user->meta['body_type'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.body_type.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="height">{{ trans(config('app.directory_name') . '/user_constants.labels.height') }}</label>
                                <select name="height"
                                        id="height"
                                        class="form-control"
                                >
                                    @if(!$user->meta['height'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.height')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('height') as $key => $value)
                                        <option
                                            {{ $user->meta['height'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.height.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="eye_color">{{ trans(config('app.directory_name') . '/user_constants.labels.eye_color') }}</label>
                                <select name="eye_color"
                                        id="eye_color"
                                        class="form-control"
                                >
                                    @if(!$user->meta['eye_color'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.eye_color')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('eye_color') as $key => $value)
                                        <option
                                            {{ $user->meta['eye_color'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.eye_color.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="hair_color">{{ trans(config('app.directory_name') . '/user_constants.labels.hair_color') }}</label>
                                <select name="hair_color"
                                        id="hair_color"
                                        class="form-control"
                                >
                                    @if(!$user->meta['hair_color'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.hair_color')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('hair_color') as $key => $value)
                                        <option
                                            {{ $user->meta['hair_color'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.hair_color.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="smoking_habits">{{ trans(config('app.directory_name') . '/user_constants.labels.smoking_habits') }}</label>
                                <select name="smoking_habits"
                                        id="smoking_habits"
                                        class="form-control"
                                >
                                    @if(!$user->meta['smoking_habits'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.smoking_habits')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('smoking_habits') as $key => $value)
                                        <option
                                            {{ $user->meta['smoking_habits'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.smoking_habits.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="drinking_habits">{{ trans(config('app.directory_name') . '/user_constants.labels.drinking_habits') }}</label>
                                <select name="drinking_habits"
                                        id="drinking_habits"
                                        class="form-control"
                                >
                                    @if(!$user->meta['drinking_habits'])
                                        <option value="" disabled
                                                selected>{{ trans(config('app.directory_name') . '/edit_profile.select_your') }} {{ strtolower(trans(config('app.directory_name') . '/user_constants.labels.drinking_habits')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('drinking_habits') as $key => $value)
                                        <option
                                            {{ $user->meta['drinking_habits'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ trans(config('app.directory_name') . '/user_constants.drinking_habits.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label
                                    for="about_me">{{ trans(config('app.directory_name') . '/user_constants.about_me') }}</label>
                                <textarea name="about_me"
                                          id="about_me"
                                          class="form-control"
                                          cols="30"
                                          rows="10"
                                          placeholder="{{ trans(config('app.directory_name') . '/edit_profile.about_me_placeholder') }}"
                                >{!! $user->meta['about_me'] !!}</textarea>
                                @include('helpers.forms.error_message', ['field' => 'about_me'])
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12">
                            <div class="text-right">
                                @include('frontend.components.button', [
                                    'buttonContext' => 'form',
                                    'buttonType' => 'submit',
                                    'buttonState' => 'primary',
                                    'buttonText' => trans(config('app.directory_name') . '/edit_profile.update')
                                ])
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="Tile EditProfile ImageGallery JS--Edit-Profile">
        <div class="Tile__heading">
            <span class="material-icons">
                collections
            </span>
            {{ trans(config('app.directory_name') . '/edit_profile.manage_images') }}
        </div>
        <div class="Tile__body">

            <div class="row">

                <form role="form" method="POST"
                      action="{!! route('users.images.update', ['userId' => $user->id]) !!}"
                      enctype="multipart/form-data"
                >
                    {!! csrf_field() !!}
                    {!! method_field('PUT') !!}

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="profile_image"
                            >
                                <span class="material-icons">
                                    add_a_photo
                                </span>
                                {{ trans(config('app.directory_name') . '/edit_profile.profile_image') }}
                            </label>
                            <input type="file" accept=".png,.jpg,.jpeg" class="form-control" id="profile_image"
                                   name="profile_image">
                            @if ($errors->has('profile_image'))
                                {!! $errors->first('profile_image', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_images"
                            >
                                <span class="material-icons">
                                    add_to_photos
                                </span>
                                {{ trans(config('app.directory_name') . '/edit_profile.upload_images') }}
                            </label>
                            <input type="file" accept=".png,.jpg,.jpeg" class="form-control" id="user_images"
                                   name="user_images[]" multiple>
                            @if ($errors->has('user_images.0'))
                                {!! $errors->first('user_images.0', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="text-right">
                            @include('frontend.components.button', [
                                'buttonContext' => 'form',
                                'buttonType' => 'submit',
                                'buttonState' => 'primary',
                                'buttonText' => trans(config('app.directory_name') . '/edit_profile.upload_photos')
                            ])
                        </div>
                    </div>

                </form>

                <div class="col-xs-12">
                    <label>{{ trans(config('app.directory_name') . '/edit_profile.your_images') }}</label>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="JS--imagesWrapper imagesWrapper {{ count($user->images) > 3 ? 'overflown' : '' }}">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="userImageFullItem {{ !$user->hasProfileImage() ? 'noImage' : '' }}">
                                    @if($user->hasProfileImage())
                                        <a href="#" class="modalImage">
                                            <div class="imageResourceWrapper">
                                        <span
                                            class="profileImageLabel">{{ trans(config('app.directory_name') . '/edit_profile.profile_image') }}</span>

                                                <img alt="profileImage" class="imageResource"
                                                     src="{!! \StorageHelper::profileImageUrl($user) !!}"
                                                />
                                            </div>
                                        </a>
                                        <form method="POST"
                                              action="{!! route('images.destroy', ['imageId' => $user->profileImage->id]) !!}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}

                                            @include('frontend.components.button', [
                                                'buttonContext' => 'form',
                                                'buttonType' => 'submit',
                                                'buttonState' => 'danger',
                                                'buttonText' => '<span class="material-icons">
                                                            delete_forever
                                                        </span>',
                                                'buttonClasses' => 'Button-fw deleteImageButton',
                                             'title' =>  trans(config('app.directory_name') . '/edit_profile.delete')
                                            ])

                                        </form>
                                    @else
                                        <div class="imageResourceWrapper">
                                    <span
                                        class="profileImageLabel">{{ trans(config('app.directory_name') . '/edit_profile.profile_image') }}
                                    </span>

                                            <img alt="profileImage" class="imageResource"
                                                 src="{!! \StorageHelper::profileImageUrl($user) !!}"
                                            />
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <?php $userImagesNotProfile = $user->imagesNotProfile; ?>
                            @foreach($userImagesNotProfile as $image)
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="userImageFullItem">
                                        <a href="#" class="modalImage">
                                            <div class="imageResourceWrapper">
                                                <img alt="galleryImage" class="imageResource"
                                                     src="{!! \StorageHelper::userImageUrl($user->id, $image->filename) !!}"
                                                />
                                            </div>
                                        </a>
                                        @include('frontend.components.button', [
                                             'url' => route('users.set-profile-image', ['userId' => $user->id, 'imageId' => $image->id]),
                                             'buttonType' => 'submit',
                                             'buttonState' => 'primary',
                                             'buttonText' => '<span class="material-icons">
                                                        account_circle
                                                    </span>',
                                             'buttonClasses' => 'Button-fw setProfileButton',
                                             'title' =>  trans(config('app.directory_name') . '/edit_profile.set_profile')
                                         ])

                                        <form method="POST"
                                              action="{!! route('images.destroy', ['imageId' => $image->id]) !!}"
                                        >
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}

                                            @include('frontend.components.button', [
                                                'buttonContext' => 'form',
                                                'buttonType' => 'submit',
                                                'buttonState' => 'danger',
                                                'buttonText' => '<span class="material-icons">
                                                                delete_forever
                                                            </span>',
                                                'buttonClasses' => 'Button-fw deleteImageButton',
                                                'title' =>  trans(config('app.directory_name') . '/edit_profile.delete')
                                            ])

                                        </form>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="Tile EditProfile JS--Edit-Profile">
        <div class="Tile__heading">
            <span class="material-icons">
                email
            </span>
            {{ trans(config('app.directory_name') . '/edit_profile.email_notification_settings') }}
        </div>
        <div class="Tile__body">

            <form role="form" method="POST"
                  action="{!! route('users.update', ['userId' => $user->id]) !!}"
                  enctype="multipart/form-data"
                  id="JS--EditProfileUserDetailsForm"
            >
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                @foreach($availableEmailTypes as $emailType)
                                    <div class="checkbox notificationSettingsItem">
                                        <label for="emailType{{ $emailType->id }}">
                                            <input
                                                id="emailType{{ $emailType->id }}"
                                                type="checkbox"
                                                value="{{ $emailType->id }}"
                                                name="email_notifications[]"
                                                {{ in_array($emailType->id, $userEmailTypeIds) ? 'checked' : '' }}
                                            >
                                            {{ trans(config('app.directory_name') . '/edit_profile.user_email_types.' . $emailType->name) }}
                                        </label>
                                        @if($emailType->id !== \App\EmailType::GENERAL)
                                            <div class="helpText">
                                                {{ trans(config('app.directory_name') . '/edit_profile.user_email_types.' . $emailType->name . '_help') }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12">
                            <div class="text-right">
                                @include('frontend.components.button', [
                                    'buttonContext' => 'form',
                                    'buttonType' => 'submit',
                                    'buttonState' => 'primary',
                                    'buttonText' => trans(config('app.directory_name') . '/edit_profile.update')
                                ])
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="Tile EditProfile JS--Edit-Profile">
        <div
            class="Tile__heading"
        >
            <span class="material-icons">
                sentiment_very_dissatisfied
            </span>
            {{ trans(config('app.directory_name') . '/edit_profile.account_deactivation') }}
        </div>
        <div class="Tile__body">
            <div class="row">
                <div class="col-xs-12">

                    <p>{{ trans(config('app.directory_name') . '/edit_profile.deactivation_text') }}</p>

                    <div class="text-left">
                        <button type="button" class="Button Button--danger" data-toggle="modal"
                                data-target=".ConfirmModalDeactivate">
                            <span
                                class="Button__content">{{ trans(config('app.directory_name') . '/edit_profile.deactivate') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.components.confirm-modal', [
        'url' => route('users.deactivate.get', ['userId' => $authenticatedUser->getId()]),
        'modalId' => 'Deactivate',
        'body' => trans(config('app.directory_name') . '/deactivation.confirmation_message')
    ])

@endsection