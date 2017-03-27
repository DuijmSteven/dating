@extends('backend.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Article</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('backend.articles.post') !!}">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text"
                                   class="form-control"
                                   id="title"
                                   name="title"
                                   required
                                   value="{{ old('title', '') }}"
                            >
                            </input>
                            @if ($errors->has('title'))
                                {!! $errors->first('title', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status"
                                    id="status"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! (old('status') === 1) ? 'selected' : '' !!}>Public</option>
                                <option value="0" {!! (old('status') === 0) ? 'selected' : '' !!}>Private</option>
                            </select>
                            @if ($errors->has('status'))
                                {!! $errors->first('status', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control"
                                      id="body"
                                      name="body"
                                      required
                                      rows="20"
                            ></textarea>
                            @if ($errors->has('body'))
                                {!! $errors->first('body', '<small class="form-error">:message</small>') !!}
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
