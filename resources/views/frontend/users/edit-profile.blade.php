@extends('frontend.layouts.default.layout')


@section('content')
    <div class="Tile EditProfile JS--Edit-Profile">
        <div class="Tile__heading">{{ trans('edit_profile.edit_profile') }}</div>
        <div class="Tile__body">
            <form role="form" class="searchForm" method="POST"
                  action="{!! route('users.update', ['userId' => $user->id]) !!}"
                  enctype="multipart/form-data">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="username">{{ trans('user_constants.username') }}</label>
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
                                <label for="city">{{ @trans('user_constants.city') }}</label>
                                <input type="text"
                                       class="JS--autoCompleteCites JS--edit form-control"
                                       name="city"
                                       value="{!! ucfirst($user->meta->city) !!}"
                                >
                                <input type="hidden"
                                       name="lat"
                                       class="js-hiddenLatInput"
                                >
                                <input type="hidden"
                                       name="lng"
                                       class="js-hiddenLngInput"
                                >
                                @if ($errors->has('city'))
                                    {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dob">{{ trans('user_constants.dob') }}</label>
                                <input type="text"
                                       class="form-control"
                                       id="dob"
                                       name="dob"
                                       disabled
                                       value="{!! $user->meta->dob->format('d M Y') !!}"
                                >
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <hr/>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="relationship_status">{{ @trans('user_constants.labels.relationship_status') }}</label>
                                <select name="relationship_status"
                                        id="relationship_status"
                                        class="form-control"
                                >
                                    @if(!$user->meta['relationship_status'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.relationship_status')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('relationship_status') as $key => $value)
                                        <option
                                            {{ $user->meta['relationship_status'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.relationship_status.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="body_type">{{ @trans('user_constants.labels.body_type') }}</label>
                                <select name="body_type"
                                        id="body_type"
                                        class="form-control"
                                >
                                    @if(!$user->meta['body_type'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.body_type')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('body_type') as $key => $value)
                                        <option
                                            {{ $user->meta['body_type'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.body_type.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="height">{{ @trans('user_constants.labels.height') }}</label>
                                <select name="height"
                                        id="height"
                                        class="form-control"
                                >
                                    @if(!$user->meta['height'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.height')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('height') as $key => $value)
                                        <option
                                            {{ $user->meta['height'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.height.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="eye_color">{{ @trans('user_constants.labels.eye_color') }}</label>
                                <select name="eye_color"
                                        id="eye_color"
                                        class="form-control"
                                >
                                    @if(!$user->meta['eye_color'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.eye_color')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('eye_color') as $key => $value)
                                        <option
                                            {{ $user->meta['eye_color'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.eye_color.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="hair_color">{{ @trans('user_constants.labels.hair_color') }}</label>
                                <select name="hair_color"
                                        id="hair_color"
                                        class="form-control"
                                >
                                    @if(!$user->meta['hair_color'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.hair_color')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('hair_color') as $key => $value)
                                        <option
                                            {{ $user->meta['hair_color'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.hair_color.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="smoking_habits">{{ @trans('user_constants.labels.smoking_habits') }}</label>
                                <select name="smoking_habits"
                                        id="smoking_habits"
                                        class="form-control"
                                >
                                    @if(!$user->meta['smoking_habits'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.smoking_habits')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('smoking_habits') as $key => $value)
                                        <option
                                            {{ $user->meta['smoking_habits'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.smoking_habits.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="drinking_habits">{{ @trans('user_constants.labels.drinking_habits') }}</label>
                                <select name="drinking_habits"
                                        id="drinking_habits"
                                        class="form-control"
                                >
                                    @if(!$user->meta['drinking_habits'])
                                        <option value="" disabled selected>{{ trans('edit_profile.select_your') }} {{ strtolower(@trans('user_constants.labels.drinking_habits')) }}</option>
                                    @endif

                                    @foreach(\UserConstants::selectableField('drinking_habits') as $key => $value)
                                        <option
                                            {{ $user->meta['drinking_habits'] === $key ? 'selected' : '' }}
                                            value="{{ $key }}">{{ @trans('user_constants.drinking_habits.' . $key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <hr/>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="about_me">{{ @trans('user_constants.about_me') }}</label>
                                <textarea name="about_me"
                                          id="about_me"
                                          class="form-control"
                                          cols="30"
                                          rows="10"
                                          placeholder="{{ @trans('edit_profile.about_me_placeholder') }}"
                                >{!! $user->meta['about_me'] !!}</textarea>
                                @include('helpers.forms.error_message', ['field' => 'about_me'])
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <hr/>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label
                                    for="email_notification_settings">{{ @trans('edit_profile.email_notification_settings') }}</label>

                                @foreach($availableEmailTypes as $emailType)
                                    <div class="checkbox notificationSettingsItem">
                                        <label>
                                            <input
                                                type="checkbox"
                                                value="{{ $emailType->id }}"
                                                name="email_notifications[]"
                                                {{ in_array($emailType->id, $userEmailTypeIds) ? 'checked' : '' }}
                                            >
                                            {{ @trans('edit_profile.user_email_types.' . $emailType->name) }}
                                        </label>
                                        <div class="helpText">
                                            {{ @trans('edit_profile.user_email_types.' . $emailType->name . '_help') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <hr/>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="user_images">{{ @trans('edit_profile.upload_images') }}</label>
                                <input type="file" accept=".png,.jpg,.jpeg" class="form-control" id="user_images" name="user_images[]" multiple>
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
                                    'buttonText' => @trans('edit_profile.update')
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-xs-12">
                    <hr/>
                </div>
            </div>

            <label for="">{{ @trans('edit_profile.manage_images') }}</label>

            <div class="table-responsive" id="images-section">
                <table class="table table-striped">
                    <?php $tableColumnAmount = 2; ?>
                    <thead>
                    <tr>
                        <th>{{ @trans('edit_profile.profile_image') }}</th>
                        <th>{{ @trans('edit_profile.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($user->hasProfileImage())
                        <tr>
                            <td>
                                <a href="#" class="modalImage">
                                    <img alt="profileImage" class="imageResource" width="200"
                                         src="{!! \StorageHelper::profileImageUrl($user) !!}"
                                    />
                                </a>
                            </td>
                            <td class="action-buttons">
                                <form method="POST"
                                      action="{!! route('images.destroy', ['imageId' => $user->profileImage->id]) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}

                                    @include('frontend.components.button', [
                                        'buttonContext' => 'form',
                                        'buttonType' => 'submit',
                                        'buttonState' => 'danger',
                                        'buttonText' => trans('edit_profile.delete'),
                                        'buttonClasses' => 'Button-fw'
                                    ])

                                </form>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="<?= $tableColumnAmount; ?>">
                                {{ @trans('edit_profile.no_profile_image_set') }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>


            <div class="table-responsive" id="images-section">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{ @trans('edit_profile.other_images') }}</th>
                        <th>{{ @trans('edit_profile.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $userImagesNotProfile = $user->imagesNotProfile; ?>
                    @if(!is_null($userImagesNotProfile))
                        @foreach($userImagesNotProfile as $image)
                            <tr>
                                <td>
                                    <a href="#" class="modalImage">
                                        <img alt="galleryImage" class="imageResource" width="200"
                                             src="{!! \StorageHelper::userImageUrl($user->id, $image->filename, true) !!}"
                                             data-src="{!! \StorageHelper::userImageUrl($user->id, $image->filename) !!}"
                                        />
                                    </a>
                                </td>
                                <td class="action-buttons">
                                    @include('frontend.components.button', [
                                        'url' => route('users.set-profile-image', ['userId' => $user->id, 'imageId' => $image->id]),
                                        'buttonType' => 'submit',
                                        'buttonState' => 'primary',
                                        'buttonText' => @trans('edit_profile.set_profile'),
                                        'buttonClasses' => 'Button-fw bottom-spacing'
                                    ])

                                    <form method="POST"
                                          action="{!! route('images.destroy', ['imageId' => $image->id]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}

                                        @include('frontend.components.button', [
                                            'buttonContext' => 'form',
                                            'buttonType' => 'submit',
                                            'buttonState' => 'danger',
                                            'buttonText' => trans('edit_profile.delete'),
                                            'buttonClasses' => 'Button-fw'
                                        ])

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="<?= $tableColumnAmount; ?>">
                                {{ @trans('edit_profile.no_images') }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <hr/>
                </div>
            </div>

            <label for="">{{ @trans('edit_profile.account_deactivation') }}</label>

            <div class="row">
                <div class="col-xs-12">

                    <p>{{ trans('edit_profile.deactivation_text') }}</p>

                    <div class="text-left">
                        <button type="button" class="Button Button--danger" data-toggle="modal" data-target=".ConfirmModalDeactivate">
                            <span class="Button__content">{{ @trans('edit_profile.deactivate') }}</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('frontend.components.confirm-modal', [
        'url' => route('users.deactivate.get', ['userId' => $authenticatedUser->getId()]),
        'modalId' => 'Deactivate',
        'body' => trans('deactivation.confirmation_message')
    ])

@endsection