@if(!isset($buttonContext) or $buttonContext == 'general' or $buttonContext != 'form')
    <a class="Button Button--{{ $buttonState or 'default' }} {{ $buttonClasses }}">
        <span class="Button__content">{{ $buttonText or 'Dummy button' }}</span>
    </a>
@else
    <button type="{{ $buttonType ?? 'submit' }}" class="Button Button--{{ $buttonState ?? 'default' }}  {{ $buttonClasses ?? '' }}">
        <span class="Button__content">{{ $buttonText ?? 'Dummy button' }}</span>
    </button>
@endif