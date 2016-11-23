@if ($errors->has($field))
    {!! $errors->first($field, '<small class="form-error">:message</small>') !!}
@endif