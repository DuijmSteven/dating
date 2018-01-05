@extends('admin.layouts.default.layout')


@section('content')


    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Send message</h3>
        </div>
        <div class="box-body">
            <form role="form" method="POST" action="" enctype="multipart/form-data">
                {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="bot_query">Search for bot</label>
                                <div>
                                    <input type="text"
                                           class="form-control"
                                           id="bot_query"
                                           name="bot_query"
                                           required
                                           value="{!! old('bot_query', '') !!}"
                                           style="display: inline-block;width: auto"
                                    >
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                @if ($errors->has('bot_query'))
                                    {!! $errors->first('bot_query', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </form>
        </div>
    </div>

@endsection
