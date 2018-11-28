@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $faqs->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Faqs (Total: <strong>{!! $faqs->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-success" href="{{ route('admin.faqs.create') }}">Create faq</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Section</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Status</th>
                                <th class="no-wrap">Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $faq)
                                <tr>
                                    <td>{!! $faq->getId() !!}</td>
                                    <td>{{ $faq->getSection() }}</td>
                                    <td>{{ $faq->getTitle() }}</td>
                                    <td>{!! $faq->getBody() !!}</td>
                                    <td>{{ \App\Faq::$statuses[$faq->getStatus()] }}</td>
                                    <td class="no-wrap">{{ $faq->getCreatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.faqs.edit', ['faqId' => $faq->getId()]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.faqs.destroy', ['faqId' => $faq->getId()]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="confirm('Are you sure you want to delete this faq?')">
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
                {!! $faqs->render() !!}
            </div>
        </div>

    </div>

@endsection
