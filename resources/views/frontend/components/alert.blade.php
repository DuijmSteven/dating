@if((session('alerts') !== null) && count(session('alerts')))
    @foreach(session('alerts') as $alert)
        <div class="alert alert--{!! $alert['type'] !!} alert-dismissible" style="border-radius: 0">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>{!! ucfirst($alert['type']) !!}!</strong> {!! $alert['message'] !!}
        </div>
    @endforeach
@endif
