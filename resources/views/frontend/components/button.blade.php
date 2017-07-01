@if(!isset($buttonContext) or $buttonContext == 'general' or $buttonContext != 'form')
    <a class="Button Button--{{ $buttonState or 'default' }}">
        <span class="Button__content">{{ $buttonText or 'Dummy button' }}</span>
    </a>
@else
    <button type="{{ $type or 'submit' }}" class="Button Button--{{ $buttonState or 'default' }}">
        <span class="Button__content">{{ $buttonText or 'Dummy button' }}</span>
    </button>
@endif