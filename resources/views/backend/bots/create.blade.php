@extends('backend.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Bot</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('backend.bots.store') !!}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <input type="hidden" value="bot" name="role">
            <input type="hidden" value="" name="email">
            <input type="hidden" type="text" value="nl" name="country">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="username"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   required
                                   value="{!! old('username', '') !!}"
                            >
                            @if ($errors->has('username'))
                                {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" name="password" value="botpassword">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">Active</label>
                            <select name="active"
                                    id="active"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! (old('active') === 1) ? 'selected' : '' !!}>Active</option>
                                <option value="0" {!! (old('active') === 0) ? 'selected' : '' !!}>Inactive</option>
                            </select>
                            @if ($errors->has('active'))
                                {!! $errors->first('active', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12"><hr/></div>

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
                                       required
                                       value="{!! old('dob', '1998-01-01') !!}"
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
                                   value="{!! old('city', '') !!}"
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
                                        {!! ($field == 'gender') ? 'required' : '' !!}
                                >
                                    @foreach(array_merge([''], $possibleOptions) as $option)
                                        <option value="{!! $option == '' ? null : $option !!}"
                                                {!! (old($field) == $option) ? 'selected' : '' !!}
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

                    <div class="col-xs-12"><hr/></div>

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
                                >{!! old($field, '') !!}</textarea>
                                @include('frontend.forms.helpers.error_message', ['field' => $field])
                            </div>
                        </div>
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

                    <div class="col-xs-12"><hr/></div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="profile_image">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image">
                            @if ($errors->has('profile_image'))
                                {!! $errors->first('profile_image', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_images">Gallery Pictures</label>
                            <input type="file" class="form-control" id="user_images" name="user_images[]" multiple>
                            @if ($errors->has('user_images'))
                                {!! $errors->first('user_images', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection
