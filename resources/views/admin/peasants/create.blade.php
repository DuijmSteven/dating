@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Peasant</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.peasants.store') !!}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <input type="hidden" value="peasant" name="role">
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
                            <label for="password">Email</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="{!! old('email', '') !!}"
                                   required>
                            @if ($errors->has('email'))
                                {!! $errors->first('email', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   value="{!! old('password', '') !!}"
                                   required>
                            @if ($errors->has('password'))
                                {!! $errors->first('password', '<small class="form-error">:message</small>') !!}
                            @endif
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
                                       class="form-control pull-right datepicker__date"
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
                                   class="js-autoCompleteDutchCites form-control"
                                   name="city"
                                   value="{!! old('city', '') !!}"
                            >
                            @if ($errors->has('city'))
                                {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::selectableFields('peasant') as $field => $possibleOptions)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <select name="{!! $field !!}"
                                        id="{!! $field !!}"
                                        class="form-control"
                                        {!! ($field == 'gender') ? 'required' : '' !!}
                                >
                                    @foreach(array_merge(['' => ''], $possibleOptions) as $key => $value)
                                        <option value="{!! $key === '' ? null : $key !!}"
                                                {!! (old($field) != '' && old($field) == $key) ? 'selected' : '' !!}
                                                >
                                            {!! $value !!}
                                        </option>
                                    @endforeach
                                </select>
                                @include('helpers.forms.error_message', ['field' => $field])
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
                    @foreach(\UserConstants::textFields('peasant') as $field)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <textarea name="{!! $field !!}"
                                          id="{!! $field !!}"
                                          class="form-control"
                                          cols="30"
                                          rows="10"
                                >{!! old($field, '') !!}</textarea>
                                @include('helpers.forms.error_message', ['field' => $field])
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
