
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
                                            @if($bot->meta->{$fieldName})
                                                <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                                </strong> {!! @trans('user_constants.' . $fieldName . '.' . $bot->meta->{$fieldName}) !!} <br>
                                            @endif
                                        @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('bot'), \UserConstants::textInputs('bot')) as $fieldName)
                                            @if($bot->meta->{$fieldName})
                                                <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                                </strong> {!! $bot->meta->{$fieldName} !!}<br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{!! route('backend.bots.edit.get', [$bot->id]) !!}" class="btn btn-default">Edit</a>

                                        <form method="POST" action="{!! route('backend.users.destroy', ['userId' => $bot->id]) !!}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="confirm('Are you sure you want to delete this bot?')">
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
                {!! $bots->render() !!}
            </div>
        </div>

    </div>

@endsection
