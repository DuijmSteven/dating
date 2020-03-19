
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $operators->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Operators (Total: <strong>{!! $operators->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profile image</th>
                            <th>Operator data</th>
                            <th>Message data</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($operators as $operator)
                            <tr>
                                <td>{!! $operator->getId() !!}</td>
                                <td>
                                    <a href="">
                                        <img width="120" src="{!! \StorageHelper::profileImageUrl($operator, true) !!}"
                                             alt="">
                                    </a>
                                </td>
                                <td>
                                    <strong>{!! @trans('user_constants.email') !!}:</strong> {!! $operator->email !!} <br>
                                    <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $operator->username !!} <br>
                                    <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($operator->meta->dob) !!} <br>
                                </td>
                                <td>
                                    <strong>All time:</strong> {!! $operator->operatorMessages->count() !!} <br>
                                    <strong>This month:</strong> {!! $operator->operatorMessagesThisMonth->count() !!} <br>
                                </td>
                                <td class="action-buttons">
{{--                                    <a href="{!! route('admin.operators.edit.get', [$operator->getId()]) !!}" class="btn btn-default">Edit</a>--}}
                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $operator->getId()]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this operator?')">
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
                {!! $operators->render() !!}
            </div>
        </div>

    </div>

@endsection
