@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Contact information</div>

            <div class="panel-body">
                <ul>
                    <li><strong>Bedrijf</strong>: {!! config('company.name') !!}</li>
                    <li><strong>KvK nr</strong>: {!! config('company.kvk') !!}</li>
                    <li><strong>BTW nr</strong>: {!! config('company.btw') !!}</li>
                    <li><strong>Adres</strong>: {!! config('company.address') !!}, {!! config('company.city') !!}</li>
                    <li><strong>E-mail adres helpdesk</strong>: {!! config('company.email') !!}</li>
                </ul>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Send a message</div>

            <div class="panel-body">
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
                        <input type="subject"
                               name="subject"
                               id="subject"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="body">Textarea</label>
                        <textarea rows="7" type="body"
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
