@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Tacs (Total: <strong>{!! $tacs->count() !!}</strong>)</h3>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Language</th>
                                <th>Content</th>
                                <th class="no-wrap">Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tacs as $tac)
                                <tr>
                                    <td>{!! $tac->getId() !!}</td>
                                    <td>{{ $tac->getLanguage() }}</td>
                                    <td>{!! $tac->getContent() !!}</td>
                                    <td class="no-wrap">{{ $tac->getCreatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.tacs.edit', ['tacId' => $tac->getId()]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.tacs.destroy', ['tacId' => $tac->getId()]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this tac?')">
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
    </div>

@endsection
