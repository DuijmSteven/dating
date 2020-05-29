@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Operator</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.operators.store') !!}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <input type="hidden" value="bot" name="role">
            <input type="hidden" value="" name="email">
            <input type="hidden" type="text" value="nl" name="country">
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
                            <input type="text" class="form-control" id="password" name="password" value="">
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
                                       value="{!! old('dob', \Carbon\Carbon::now()->subYears(25)->format('d-m-Y')) !!}"
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
                                   class="JS--autoCompleteCites form-control"
                                   name="city"
                                   value="{!! old('city', '') !!}"
                            >
                            @if ($errors->has('city'))
                                {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Gender</label>
                            <select name="gender"
                                    id="gender"
                                    class="form-control"
                                    required
                            >
                                <option value="{{ \App\User::GENDER_MALE }}"
                                    {!! old('gender') == '' || old('gender') == '1' ? 'selected' : '' !!}
                                >Male</option>
                                <option value="{{ \App\User::GENDER_FEMALE }}"
                                    {!! old('gender') == '2' ? 'selected' : '' !!}
                                >Female</option>
                            </select>
                        </div>
                    </div>

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
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection
