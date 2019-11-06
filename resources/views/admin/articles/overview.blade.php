@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $articles->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Articles (Total: <strong>{!! $articles->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-success" href="{{ route('admin.articles.create') }}">Create article</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Status</th>
                                <th>Image</th>
                                <th class="no-wrap">Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td>{!! $article->id !!}</td>
                                    <td>{{ $article->title }}</td>
                                    <td>{!! $article->body !!}</td>
                                    <td>{{ \App\Article::$statuses[$article->status] }}</td>
                                    <td>
                                        @if($article->image_filename)
                                            <a href="{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename) !!}">
                                                <img width="120" src="{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename, true) !!}" alt="">
                                            </a>
                                        @endif
                                    </td>
                                    <td class="no-wrap">{{ $article->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.articles.edit', ['articleId' => $article->id]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.articles.destroy', ['articleId' => $article->id]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this article?')">
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
                {!! $articles->render() !!}
            </div>
        </div>

    </div>

@endsection
