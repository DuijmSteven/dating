@extends('backend.layouts.default.layout')



@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create User</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{{ route('backend.bots.create') }}" enctype="multipart/form-data">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="botpassword" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="profilePic">Profile Picture</label>
                            <input type="file" class="form-control" id="profilePic" name="profilePic" required autofocus>
                        </div>
                    </div>
                    <div class="col-xs-12"><hr/></div>
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
                                        <option value="{{ $option == '' ? null : $option }}"
                                                {{ (old($field) == $option) ? 'selected' : '' }}
                                        >
                                            {{ ucfirst(str_replace('_', ' ', $option)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @include('frontend.forms.helpers.error_message', ['field' => $field])
                            </div>
                        </div>
                        {{-- Prevents breaking when error on > xs viewports --}}
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
                                ></textarea>
                                @include('frontend.forms.helpers.error_message', ['field' => $field])
                            </div>
                        </div>
                    @endforeach
                    @if($counter % 2)
                        <div class="col-xs-12"></div>
                    @endif
                    <?php $counter++; ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>




@endsection
