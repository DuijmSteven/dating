
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $editors->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Editors (Total: <strong>{!! $editors->total() !!}</strong>)</h3>
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
                        @foreach($editors as $editor)
                            <tr>
                                <td>{!! $editor->getId() !!}</td>
                                <td>
                                    <a href="">
                                        <img width="120" src="{!! \StorageHelper::profileImageUrl($editor, true) !!}"
                                             alt="">
                                    </a>
                                </td>
                                <td>
                                    <h5 class="statsHeading"><strong>Created bots</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {!! $editor->createdBots->count() !!} <br>
                                        <strong>Last month:</strong> {!! $editor->createdBotsLastMonth->count() !!} <br>
                                        <strong>This month:</strong> {!! $editor->createdBotsThisMonth->count() !!} <br>
                                        <strong>Last week:</strong> {!! $editor->createdBotsLastWeek->count() !!} <br>
                                        <strong>This week:</strong> {!! $editor->createdBotsThisWeek->count() !!} <br>
                                        <strong>Yesterday:</strong> {!! $editor->createdBotsYesterday->count() !!} <br>
                                        <strong>Today:</strong> {!! $editor->createdBotsToday->count() !!} <br>

                                    </div>
                                </td>
                                <td>
                                    <strong>{!! @trans('user_constants.email') !!}:</strong> {!! $editor->email !!} <br>
                                    <strong>{!! @trans('user_constants.username') !!}:</strong> {!! $editor->username !!} <br>
                                    <strong>{!! @trans('user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($editor->meta->dob) !!} <br>
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.editors.created-bots.overview', [$editor->getId()]) !!}" class="btn btn-default">Created bots <b>({{ $editor->created_bots_count }})</b></a>

                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $editor->getId()]) !!}">
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
                {!! $editors->render() !!}
            </div>
        </div>

    </div>

@endsection
