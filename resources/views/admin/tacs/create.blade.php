@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Tac</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.tacs.post') !!}">
            {!! csrf_field() !!}

            <input type="hidden" name="language" value="nl">

            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control"
                                      id="content"
                                      name="content"
                                      required
                                      rows="20"
                                      data-provide="markdown"
                            ></textarea>
                            @if ($errors->has('content'))
                                {!! $errors->first('content', '<small class="form-error">:message</small>') !!}
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
