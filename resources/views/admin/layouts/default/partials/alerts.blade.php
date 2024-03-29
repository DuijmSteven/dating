@if((session('alerts') !== null) && count(session('alerts')))
    @foreach(session('alerts') as $alert)
        <div class="alert alert-{!! $alert['type'] !!} alert-dismissible" style="border-radius: 0">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" style="text-decoration: none">&times;</a>
            <strong>{!! ucfirst($alert['type']) !!}!</strong> {!! $alert['message'] !!}
        </div>
    @endforeach
@endif

@if(is_null(session('alerts')) && (isset($errors)) && $errors->count() > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-error alert-dismissible" style="border-radius: 0">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" style="text-decoration: none">&times;</a>
            <strong>Error:</strong> {!! $error !!}
        </div>
    @endforeach
@endif