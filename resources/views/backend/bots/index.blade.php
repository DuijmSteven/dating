@extends('backend.layouts.default.layout')



@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Bots (Total: <strong>{!! $bots->total() !!}</strong>)</h3>
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
                            @foreach($bots as $bot)
                                <tr>
                                    <td>{!! $bot->id !!}</td>
                                    <td>
                                        <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $bot->username !!} <br>
                                        <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($bot->meta->dob) !!} <br>
                                    </td>
                                    <td>
                                        @foreach(\UserConstants::selectableFields('bot') as $fieldName => $a)
                                            <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                            </strong> {!! @trans('user_constants.' . $fieldName . '.' . $bot->meta->{$fieldName}) !!} <br>
                                        @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('bot'), \UserConstants::textInputs('bot')) as $fieldName)
                                            <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                            </strong> {!! $bot->meta->{$fieldName} !!}<br>
                                        @endforeach
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{!! route('backend.bots.edit.get', [$bot->id]) !!}" class="btn btn-default">Edit</a>
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
                {!! $bots->render() !!}
            </div>
        </div>

    </div>

@endsection
