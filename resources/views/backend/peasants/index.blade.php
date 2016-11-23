@extends('backend.layouts.default.layout')



@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Peasants (Total: <strong>{!! $peasants->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User data</th>
                                <th>Meta data</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peasants as $peasant)
                                <tr>
                                    <td>{!! $peasant->id !!}</td>
                                    <td>
                                        <strong>Username:</strong> {!! $peasant->username !!} <br>
                                        <strong>Email:</strong> {!! $peasant->email !!} <br>
                                        <strong>Age:</strong> {!! $carbonNow->diffInYears($peasant->meta->dob) !!} <br>
                                    </td>
                                    <td>
                                        @foreach(\UserConstants::publicFieldNames('peasant') as $fieldName)
                                            <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                            </strong> {!! ucfirst(str_replace('_', ' ', $peasant->meta->{$fieldName})) !!} <br>
                                        @endforeach
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{!! route('backend.peasants.edit.get', [$peasant->id]) !!}" class="btn btn-default">Edit</a>
                                        <a href="" class="btn btn-danger" disabled>Delete</a>
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
                {!! $peasants->render() !!}
            </div>
        </div>

    </div>

@endsection
