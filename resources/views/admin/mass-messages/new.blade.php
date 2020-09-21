@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">New Mass Message</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.mass-messages.send') !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="body">Message Body</label>
                            <textarea name="body"
                                      id="body"
                                      class="form-control"
                                      cols="30"
                                      rows="10"
                                      required
                            >{!! old('body', '') !!}</textarea>
                            @include('helpers.forms.error_message', ['field' => 'body'])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <h4>Limitation of reach</h4>

                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message1">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message1" value="unlimited" checked>
                                    ({{ $userCounts['unlimited'] }}) Unlimited
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message2">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message2" value="limited_no_pic">
                                    ({{ $userCounts['withoutPic'] }}) Only users that have no pictures and very few
                                    fields filled
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message3">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message3" value="limited_with_pic">
                                    ({{ $userCounts['withPic'] }}) Only users that have filled at least one major
                                    profile field (any uploaded
                                    picture, city, dob, about me)
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message4">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message4" value="limited_have_payed">
                                    ({{ $userCounts['havePayed'] }}) Only users that have bought at least once
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message5">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message5" value="limited_have_payed_and_no_images">
                                    ({{ $userCounts['havePayedAndDontHaveImages'] }}) Only users that have bought at
                                    least once and have no images
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message6">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message6" value="limited_today">
                                    ({{ $userCounts['registeredToday'] }}) Only today's registrations
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message7">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message7" value="limited_yesterday">
                                    ({{ $userCounts['registeredYesterday'] }}) Only yesterday's registrations
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="limit_message8">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message8" value="limited_yesterday_up_to_four_days_ago">
                                    ({{ $userCounts['registeredYesterdayUpToFourDaysAgo'] }}) Registrations from
                                    yesterday up to 4 days ago
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="limit_message8">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message8" value="limited_5_up_to_9_days_ago">
                                    ({{ $userCounts['registered5UpTo9DaysAgo'] }}) Registrations from 5 up to 9 days ago
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="limit_message8">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message8" value="limited_10_up_to_19_days_ago">
                                    ({{ $userCounts['registered10UpTo19DaysAgo'] }}) Registrations from 10 up to 19 days
                                    ago
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="limit_message8">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message8" value="limited_20_up_to_29_days_ago">
                                    ({{ $userCounts['registered20UpTo29DaysAgo'] }}) Registrations from 20 up to 29 days
                                    ago
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="limit_message8">
                                    <input class="form-check-input" type="radio" name="limit_message"
                                           id="limit_message8" value="limited_30_up_to_39_days_ago">
                                    ({{ $userCounts['registered30UpTo39DaysAgo'] }}) Registrations from 30 up to 39 days
                                    ago
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Past mass messages sent</h3>
        </div>
        <div class="table-responsive box-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Body</th>
                    <th>Sent to # of peasants</th>
                    <th class="no-wrap">Created at</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pastMassMessages as $message)
                    <tr>
                        <td>{{ $message->getId() }}</td>
                        <td>{{ $message->getDescription() }}</td>
                        <td>{{ $message->getBody() }}</td>
                        <td>{{ $message->getUserCount() }}</td>
                        <td>
                            {{ $message->getCreatedAt()->format('d-m-Y H:i:s') }}
                            ({{ $message->getCreatedAt()->diffForHumans() }})
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
