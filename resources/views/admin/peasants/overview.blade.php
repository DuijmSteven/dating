
@extends('admin.layouts.default.layout')



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
                            <th>Profile image</th>
                            <th>Credits</th>
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
                                    <a href="">
                                        <img width="120" src="{!! \StorageHelper::profileImageUrl($peasant, true) !!}"
                                             alt="">
                                    </a>
                                </td>
                                <td>{{ $peasant->account->getCredits() }}</td>
                                <td>
                                    <strong>{!! @trans('user_constants.email') !!}:</strong> {!! $peasant->email !!} <br>
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
                                        @if(isset($peasant->meta->{$fieldName}) && $peasant->meta->{$fieldName} != '')
                                            <strong>{!! @trans('user_constants.' . $fieldName) !!}:
                                            </strong> {!! $peasant->meta->{$fieldName} !!}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.peasants.edit.get', [$peasant->id]) !!}" class="btn btn-default">Edit</a>

                                    <a href="{!! route('admin.peasants.message-as-bot.get', [$peasant->id]) !!}" class="btn btn-default">Message as a bot</a>

                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $peasant->id]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this peasant?')">
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
