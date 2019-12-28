@extends('frontend.layouts.default.layout')

@section('content')

    <div class="Tile Tile__privacy">
        <div class="Tile__heading">
            <h4>Algemene Voorwaarden Altijdsex.nl</h4>
        </div>
        <div
                class="Tile__body"
        >
            {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($tac->getContent()) !!}
        </div>
    </div>

@endsection
