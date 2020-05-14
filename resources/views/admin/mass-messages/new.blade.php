@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">New Mass Message</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.mass-messages.send') !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="body">Message Body</label>
                            <textarea name="body"
                                      id="body"
                                      class="form-control"
                                      cols="30"
                                      rows="10"
                                      required
                            >{!! old('body', '') !!}</textarea>
                            @include('helpers.forms.error_message', ['field' => 'body'])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="limited_to_filled_profiles">
                                Limit message to users that have filled at least one major profile field (any uploaded
                                <picture></picture> ,
                                city, dob, about me)
                                <input
                                    type="checkbox"
                                    name="limited_to_filled_profiles"
                                    value="1"
                                    checked
                                >
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Past mass messages sent</h3>
        </div>
        <div class="box-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Body</th>
                        <th class="no-wrap">Created at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pastMassMessages as $message)
                        <tr>
                            <td>{{ $message->getId() }}</td>
                            <td>{{ $message->getBody() }}</td>
                            <td>
                                {{ $message->getCreatedAt()->format('d-m-Y H:i:s') }}
                                ({{ $message->getCreatedAt()->diffForHumans() }})
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
