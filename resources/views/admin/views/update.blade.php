@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit View</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.views.update', ['viewId' => $view->id]) !!}">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title">Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   required
                                   value="{{ $view->name }}"
                            >
                            </input>
                            @if ($errors->has('name'))
                                {!! $errors->first('name', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
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
