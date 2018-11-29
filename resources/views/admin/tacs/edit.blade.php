@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Tac</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.tacs.update', ['tacId' => $tac->getId()]) !!}">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control"
                                      id="content"
                                      name="content"
                                      required
                                      rows="40"
                                      data-provide="markdown"
                            >{!! $tac->getContent() !!}</textarea>
                            @if ($errors->has('content'))
                                {!! $errors->first('content', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Update Tac</button>
            </div>
        </form>
    </div>

@endsection
