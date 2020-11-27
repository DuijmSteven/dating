@if(!isset($buttonContext) or $buttonContext == 'general' or $buttonContext != 'form')
    <a
        href="{!! $url !!}"
        class="Button Button--{{ $buttonState ?? 'default' }} {{ $buttonClasses ?? '' }}"
        title="{{ isset($title) ? $title : '' }}"
    >
        <span class="Button__content">{!! $buttonText ?? 'Dummy button' !!}</span>
    </a>
@else
    <button
        type="{{ isset($buttonType) ? $buttonType : 'submit' }}"
        class="Button Button--{{ $buttonState ?? 'default' }}  {{ $buttonClasses ?? '' }}"
        title="{{ isset($title) ? $title : '' }}"
    >
        <span class="Button__content">{!! $buttonText ?? 'Dummy button' !!}</span>
    </button>
@endif