@extends('frontend.layouts.default.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('users/upload') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="inputFile" class="col-md-4 control-label">File</label>
                            <div class="col-md-6">
                                <input id="inputFile" type="file" class="form-control" name="photo" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
