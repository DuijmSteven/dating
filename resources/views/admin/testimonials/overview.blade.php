@extends('admin.layouts.default.layout')


@section('content')

    <div class="row">

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $testimonials->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">Testimonials (Total: <strong>{!! $testimonials->total() !!}</strong>)</h3>
                    </div>
                    <a class="pull-right btn btn-success" href="{{ route('admin.testimonials.create') }}">Create testimonial</a>
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
                                <th class="no-wrap">Pretend at</th>
                                <th class="no-wrap">Created at</th>
                                <th class="no-wrap">Updated at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testimonials as $testimonial)
                                <tr>
                                    <td>{!! $testimonial->getId() !!}</td>
                                    <td>{{ $testimonial->getTitle() }}</td>
                                    <td>{!! $testimonial->getBody() !!}</td>
                                    <td>{{ \App\Testimonial::$statuses[$testimonial->getStatus()] }}</td>
                                    <td class="no-wrap">{{ $testimonial->getCreatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="no-wrap">{{ $testimonial->getUpdatedAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="no-wrap">{{ $testimonial->getPretendAt()->format('d-m-Y H:i:s') }}</td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.testimonials.edit', ['testimonialId' => $testimonial->getid()]) }}"
                                           class="btn btn-default">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.testimonials.destroy', ['testimonialId' => $testimonial->getid()]) }}">
                                            {!! csrf_field() !!}
                                            {!! method_field('DELETE') !!}
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this testimonial?')">
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
                {!! $testimonials->render() !!}
            </div>
        </div>

    </div>

@endsection
