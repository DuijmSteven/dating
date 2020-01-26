@if(!isset($buttonContext) or $buttonContext == 'general' or $buttonContext != 'form')
    <a
        href="{!! $url !!}"
        class="Button Button--{{ $buttonState ?? 'default' }} {{ $buttonClasses ?? '' }}">
        <span class="Button__content">{{ $buttonText ?? 'Dummy button' }}</span>
    </a>
@else
    <button
        type="{{ $buttonType ?? 'submit' }}"
        class="Button Button--{{ $buttonState ?? 'default' }}  {{ $buttonClasses ?? '' }}"
    >
        <span class="Button__content">{!! $buttonText ?? 'Dummy button' !!}</span>
    </button>
@endif