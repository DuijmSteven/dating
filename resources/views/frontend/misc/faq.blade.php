@extends('frontend.layouts.default.layout')

@section('content')

    <div class="Tile Tile__privacy">
        <div class="Tile__heading">
            <h4>{{ trans('faq.faq') }}</h4>
        </div>
        <div
                class="Tile__body"
        >
            <div class="row">
                @foreach($faqSections as $section)
                    <div class="col-xs-12">
                        <div class="Faq__guide">
                            <a class="Faq__guide__section" href="#{{ $section }}">
                                {{ $section }}
                            </a>

                            <ol class="Faq__guide__list">
                                @foreach($faqs as $faq)
                                    @if($faq->getSection() == $section)
                                        <li>
                                            <a href="#{{ $section }}-{{ $faq->getId() }}">
                                                {{ $faq->getTitle() }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach

                            </ol>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>
            @foreach($faqSections as $section)
                <h3 id="{{ $section }}">{{ $section }}</h3>
                @foreach($faqs as $faq)
                    @if($faq->getSection() == $section)
                        <h4 id="{{ $section }}-{{ $faq->getId() }}">{{ $faq->getTitle() }}</h4>
                        <div>
                            {{ $faq->getBody() }}
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

@endsection
