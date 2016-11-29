
@extends('backend.layouts.default.layout')



@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $peasants->render() !!}
            </div>
        </div>

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
                                    <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $peasant->username !!} <br>
                                    <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($peasant->meta->dob) !!} <br>
                                </td>
                                <td>
                                    @foreach(\UserConstants::selectableFields('peasant') as $fieldName => $a)
                                        @if(isset($peasant->meta->{$fieldName}))
                                            <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                            </strong> {!! @trans('user_constants.' . $fieldName . '.' . $peasant->meta->{$fieldName}) !!} <br>
                                        @endif
                                    @endforeach

                                    @foreach(array_merge(\UserConstants::textFields('peasant'), \UserConstants::textInputs('peasant')) as $fieldName)
                                        @if(isset($peasant->meta->{$fieldName}))
                                            <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                            </strong> {!! $peasant->meta->{$fieldName} !!}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('backend.peasants.edit.get', [$peasant->id]) !!}" class="btn btn-default">Edit</a>

                                    <form method="POST" action="{!! route('backend.users.destroy', ['userId' => $peasant->id]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="confirm('Are you sure you want to delete this peasant?')">
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
                {!! $peasants->render() !!}
            </div>
        </div>

    </div>

@endsection
