@extends('frontend.layouts.default.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="Tile">
            <div class="Tile__heading">{{ @trans('contact.heading') }}</div>
            <div class="Tile__body">
                <ul>
                    <li><strong>{{ @trans('contact.company') }}</strong>: {!! config('company.name') !!}</li>
                    <li><strong>{{ @trans('contact.kvk_number') }}</strong>: {!! config('company.kvk') !!}</li>
                    <li><strong>{{ @trans('contact.btw_number') }}</strong>: {!! config('company.btw') !!}</li>
                    <li><strong>{{ @trans('contact.address') }}</strong>: {!! config('company.address') !!}, {!! config('company.city') !!}</li>
                    <li><strong>{{ @trans('contact.support_email') }}</strong>: {!! config('company.email') !!}</li>
                </ul>
            </div>
        </div>

        <div class="Tile">
            <div class="Tile__heading">{{ @trans('contact.send_a_message') }}</div>
            <div class="Tile__body">
                <form action="" class="form" role="form">
                    <div class="form-group">
                        <label for="email">{{ @trans('contact.email') }}</label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="subject">{{ @trans('contact.subject') }}</label>
                        <input type="text"
                               name="subject"
                               id="subject"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="body">{{ @trans('contact.message') }}</label>
                        <textarea rows="7"
                               name="body"
                               id="body"
                               class="form-control">
                        </textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit"
                                class="btn btn-default">
                            {{ @trans('contact.send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
