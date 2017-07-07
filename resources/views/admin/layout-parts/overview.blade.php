@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $layoutParts->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Layout Parts (Total: <strong>{!! $layoutParts->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-info" href="{{ route('admin.layout-parts.create') }}">Create Layout Part</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($layoutParts as $layoutPart)
                                <tr>
                                    <td>{!! $layoutPart->getId() !!}</td>
                                    <td>{{ $layoutPart->getName() }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.layout-parts.edit', ['layoutPartId' => $layoutPart->id]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.layout-parts.destroy', ['layoutPartId' => $layoutPart->id]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="confirm('Are you sure you want to delete this layout part?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $layoutParts->render() !!}
            </div>
        </div>

    </div>

@endsection
