@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="Tile">
            <div class="Tile__heading">Contact information</div>
            <div class="Tile__body">
                <ul>
                    <li><strong>Bedrijf</strong>: {!! config('company.name') !!}</li>
                    <li><strong>KvK nr</strong>: {!! config('company.kvk') !!}</li>
                    <li><strong>BTW nr</strong>: {!! config('company.btw') !!}</li>
                    <li><strong>Adres</strong>: {!! config('company.address') !!}, {!! config('company.city') !!}</li>
                    <li><strong>E-mail adres helpdesk</strong>: {!! config('company.email') !!}</li>
                </ul>
            </div>
        </div>

        <div class="Tile">
            <div class="Tile__heading">Send a message</div>
            <div class="Tile__body">
                <form action="" class="form" role="form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text"
                               name="subject"
                               id="subject"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="body">Textarea</label>
                        <textarea rows="7"
                               name="body"
                               id="body"
                               class="form-control">
                        </textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit"
                                class="btn btn-default">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
