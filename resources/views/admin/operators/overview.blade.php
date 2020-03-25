
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
                            <th>Stats</th>
                            <th>Operator data</th>
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
                                    <h5 class="statsHeading"><strong>Messages sent</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {!! $operator->operator_messages_count !!} <br>
                                        <strong>This month:</strong> {!! $operator->operator_messages_this_month_count !!} <br>
                                    </div>
                                </td>
                                <td>
                                    <strong>{!! @trans('user_constants.email') !!}:</strong> {!! $operator->email !!} <br>
                                    <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $operator->username !!} <br>
                                    <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($operator->meta->dob) !!} <br>
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.operators.edit.get', ['operatorId' => $operator->id]) !!}" class="btn btn-default">Edit</a>
                                    <a href="{!! route('admin.conversations.with-operator', ['operatorId' => $operator->getId()]) !!}" class="btn btn-default">Conversations <b>({{ $operator->conversationsAsOperator()->withTrashed()->count() }})</b></a>
                                    <a href="{!! route('admin.operators.messages.overview', ['operatorId' => $operator->getId()]) !!}" class="btn btn-default">Sent messages <b>({{ $operator->operator_messages_count }})</b></a>
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
