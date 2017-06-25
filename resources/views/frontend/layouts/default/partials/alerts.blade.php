@if((session('alerts') !== null) && count(session('alerts')))
    @foreach(session('alerts') as $alert)
        <div class="alert alert-{!! $alert['type'] !!} alert-dismissible" style="border-radius: 0">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{!! ucfirst($alert['type']) !!}!</strong> {!! $alert['message'] !!}
        </div>
    @endforeach
@endif
