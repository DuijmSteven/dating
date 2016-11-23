@extends('backend.layouts.default.layout')



@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create User/h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form">
            <div class="box-body">
                <div class="row">

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::SELECTABLE_PROFILE_FIELDS as $field => $possibleOptions)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="query">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
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
                                @include('helpers.forms.error_message', ['field' => $field])
                            </div>
                        </div>

                        {{-- Prevents breaking when error on > xs viewports --}}
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                    </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>




@endsection
