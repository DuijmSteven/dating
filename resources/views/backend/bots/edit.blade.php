@extends('backend.layouts.default.layout')


@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Bot</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('backend.bots.update', ['id' => $user->id]) !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   required
                                   value="{!! $user->username !!}"
                            >
                            @if ($errors->has('username'))
                                {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" name="password"
                                   value="botpassword">
                        </div>
                        @if ($errors->has('password'))
                            {!! $errors->first('password', '<small class="form-error">:message</small>') !!}
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">Active</label>
                            <select name="active"
                                    id="active"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! ($user->active == 1) ? 'selected' : '' !!}>Active</option>
                                <option value="0" {!! ($user->active == 0) ? 'selected' : '' !!}>Inactive</option>
                            </select>
                            @if ($errors->has('active'))
                                {!! $errors->first('active', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Date of birth:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text"
                                       class="form-control pull-right"
                                       id="datepicker-bot-create"
                                       name="dob"
                                       value="{!! $user->meta->dob->format('Y-m-d') !!}"
                                >
                                @if ($errors->has('dob'))
                                    {!! $errors->first('dob', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">City</label>
                            <input type="text"
                                   class="js-autocompleteDutchCites form-control"
                                   name="city"
                                   value="{!! $user->meta->city !!}"
                            >
                            @if ($errors->has('city'))
                                {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::SELECTABLE_PROFILE_FIELDS as $field => $possibleOptions)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <select name="{!! $field !!}"
                                        id="{!! $field !!}"
                                        class="form-control"
                                >
                                    @foreach(array_merge([''], $possibleOptions) as $option)
                                        <option value="{!! $option == '' ? null : $option !!}"
                                                {!! ($user->meta[$field] == $option) ? 'selected' : '' !!}
                                        >
                                            {!! ucfirst(str_replace('_', ' ', $option)) !!}
                                        </option>
                                    @endforeach
                                </select>
                                @include('frontend.forms.helpers.error_message', ['field' => $field])
                            </div>
                        </div>
                        {{--Prevents breaking when error on > xs viewports--}}
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::TEXTFIELD_PROFILE_FIELDS as $field)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <textarea name="{!! $field !!}"
                                          id="{!! $field !!}"
                                          class="form-control"
                                          cols="30"
                                          rows="10"
                                >{!! $user->meta[$field] !!}</textarea>
                                @include('frontend.forms.helpers.error_message', ['field' => $field])
                            </div>
                        </div>
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_images">Gallery Pictures</label>
                            <input type="file" class="form-control" id="user_images" name="user_images[]" multiple>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped">
                <?php $tableColumnAmount = 3; ?>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Visible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="<?= $tableColumnAmount; ?>">
                        Profile Image
                    </td>
                </tr>
                <?php $userProfileImage = $user->profileImage; ?>
                @if(count($userProfileImage) > 0)
                    @foreach($userProfileImage as $image)
                        <tr>
                            <td>
                                <img width="200" src="{!! \StorageHelper::userImageUrl($user->id, $image['filename']) !!}"/>
                            </td>
                            <td>
                                <?= ($image->visible) ? 'Yes' : 'No' ; ?>
                            </td>
                            <td class="action-buttons">
                                <form method="POST" action="{!! route('images.destroy', ['imageId' => $image->id]) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="<?= $tableColumnAmount; ?>">
                            No profile image set
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="<?= $tableColumnAmount; ?>">
                        Other Images
                    </td>
                </tr>

                <?php $userImagesNotProfile = $user->imagesNotProfile; ?>
                @if(count($userImagesNotProfile) > 0)
                    @foreach($userImagesNotProfile as $image)
                        <tr>
                            <td>
                                <img width="200" src="{!! \StorageHelper::userImageUrl($user->id, $image['filename']) !!}"/>
                            </td>
                            <td>
                                <?= ($image->visible) ? 'Yes' : 'No' ; ?>
                            </td>
                            <td class="action-buttons">
                                <form method="POST" action="{!! route('images.destroy', ['imageId' => $image->id]) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{!! route('images.set_profile', ['imageId' => $image->id]) !!}" class="btn btn-success">Set profile</a>
                                <a href="{!! route('images.toggle_visibility', ['imageId' => $image->id]) !!}" class="btn btn-default">Toggle visibility</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="<?= $tableColumnAmount; ?>">
                            No images found
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
