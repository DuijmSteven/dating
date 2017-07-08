@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $views->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Views (Total: <strong>{!! $views->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-info" href="{{ route('admin.views.create.show') }}">Create View</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Route name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($views as $view)
                                <tr>
                                    <td>{!! $view->getId() !!}</td>
                                    <td>{{ $view->getName() }}</td>
                                    <td>{{ $view->getRouteName() }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.views.delete', ['viewId' => $view->id]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.views.delete', ['viewId' => $view->id]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="confirm('Are you sure you want to delete this view?')">
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
                {!! $views->render() !!}
            </div>
        </div>

    </div>

@endsection
