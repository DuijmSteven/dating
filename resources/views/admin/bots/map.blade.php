
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div id="map_canvas" style="height: 1000px">
                {!! \Mapper::render() !!}
            </div>
        </div>

    </div>

@endsection
