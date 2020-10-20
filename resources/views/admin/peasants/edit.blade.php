@extends('admin.layouts.default.layout')


@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Peasant</h3>
        </div>

        <div style="margin-bottom: 20px; padding: 10px 10px 0">
            <a href="{!! route('admin.conversations.peasant.get', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Conversations <b>({{ $peasant->conversations_as_user_a_count + $peasant->conversations_as_user_b_count }})</b></a>
            <a href="{!! route('admin.messages.peasant', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Messages <b>({{ $peasant->messages_count +  $peasant->messaged_count}})</b></a>
            <a href="{!! route('admin.payments.peasant.overview', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Payments <b>({{ $peasant->payments_count}})</b></a>
            <a href="{!! route('admin.peasants.message-as-bot.get', ['peasantId' => $peasant->id, 'onlyOnlineBots' => '0']) !!}" class="btn btn-default">Message user as bot</a>
            <a href="{!! route('admin.peasants.message-as-bot.get', [ 'peasantId' => $peasant->id, 'onlyOnlineBots' => '1']) !!}" class="btn btn-default">Message user as online bot</a>

            <form
                method="POST"
                action="{!! route('admin.peasants.destroy', ['id' => $peasant->getId()]) !!}"
                style="display: inline-block"
            >
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <button type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this peasant?')">
                    Delete
                </button>
            </form>
        </div>

        @if($peasantMessagesChart)
            <div style="margin-bottom: 20px; padding: 10px 10px 0">
                <div style="width: 100%">
                    {!! $peasantMessagesChart->container() !!}
                    {!! $peasantMessagesChart->script() !!}
                </div>
            </div>
        @endif

        @if($peasantMessagesMonthlyChart)
            <div style="margin-bottom: 20px; padding: 10px 10px 0">
                <div style="width: 100%">
                    {!! $peasantMessagesMonthlyChart->container() !!}
                    {!! $peasantMessagesMonthlyChart->script() !!}
                </div>
            </div>
        @endif

        @if($peasantPaymentsChart)
            <div style="margin-bottom: 20px; padding: 10px 10px 0">
                <div style="width: 100%">
                    {!! $peasantPaymentsChart->container() !!}
                    {!! $peasantPaymentsChart->script() !!}
                </div>
            </div>
    @endif

        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.peasants.update', ['userId' => $peasant->id]) !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">

                <div class="userStats">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages received</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $peasant->messaged_count }} <br>
                                <strong>Last month:</strong> {{ $peasant->messaged_last_month_count }} <br>
                                <strong>This month:</strong> {{ $peasant->messaged_this_month_count }} <br>
                                <strong>Last Week:</strong> {{ $peasant->messaged_last_week_count }} <br>
                                <strong>This week:</strong> {{ $peasant->messaged_this_week_count }} <br>
                                <strong>Yesterday:</strong> {{ $peasant->messaged_yesterday_count }} <br>
                                <strong>Today:</strong> {{ $peasant->messaged_today_count }} <br>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages sent</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $peasant->messages_count }} <br>
                                <strong>Last month:</strong> {{ $peasant->messages_last_month_count }} <br>
                                <strong>This month:</strong> {{ $peasant->messages_this_month_count }} <br>
                                <strong>Last Week:</strong> {{ $peasant->messages_last_week_count }} <br>
                                <strong>This week:</strong> {{ $peasant->messages_this_week_count }} <br>
                                <strong>Yesterday:</strong> {{ $peasant->messages_yesterday_count }} <br>
                                <strong>Today:</strong> {{ $peasant->messages_today_count }} <br>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Viewed</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $peasant->hasViewed->count()  }} <br>
                                <strong>Unique:</strong> {{ $peasant->hasViewedUnique()->get()->count() }}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages received/sent ratio (smaller is better)</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $peasant->messagedVsMessagesPercentage() }} ({{ $peasant->messaged_count }} / {{ $peasant->messages_count }}) <br>
                                <strong>Last month:</strong> {{ $peasant->messagedVsMessagesPercentageLastMonth() }} ({{ $peasant->messaged_last_month_count }} / {{ $peasant->messages_last_month_count }}) <br>
                                <strong>This month:</strong> {{ $peasant->messagedVsMessagesPercentageThisMonth() }} ({{ $peasant->messaged_this_month_count }} / {{ $peasant->messages_this_month_count }}) <br>
                                <strong>Last Week:</strong> {{ $peasant->messagedVsMessagesPercentageLastWeek() }} ({{ $peasant->messaged_last_week_count }} / {{ $peasant->messages_last_week_count }}) <br>
                                <strong>This week:</strong> {{ $peasant->messagedVsMessagesPercentageThisWeek() }} ({{ $peasant->messaged_this_week_count }} / {{ $peasant->messages_this_week_count }}) <br>
                                <strong>Yesterday:</strong> {{ $peasant->messagedVsMessagesPercentageYesterday() }} ({{ $peasant->messaged_yesterday_count }} / {{ $peasant->messages_yesterday_count }}) <br>
                                <strong>Today:</strong> {{ $peasant->messagedVsMessagesPercentageToday() }} ({{ $peasant->messaged_today_count }} / {{ $peasant->messages_today_count }}) <br>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            @php
                                $highlightTypeClass = '';

                                if ($peasant->account->getCredits() > 10) {
                                    $highlightTypeClass = 'success';
                                } else if ($peasant->account->getCredits() > 4) {
                                    $highlightTypeClass = 'warning';
                                } else {
                                    $highlightTypeClass = 'error';
                                }
                            @endphp

                            <h5 class="innerTableWidgetHeading"><strong>Peasant data</strong></h5>
                            <div class="innerTableWidgetBody">
                                <strong>Credits</strong>: <span class="highlightAsDisk {{ $highlightTypeClass }}">{{ $peasant->account->getCredits() }}</span> <br>
                                <strong>Created at</strong> {!! $peasant->getCreatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                ({!! $peasant->getCreatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                            </div>
                        </div>

                        @if($peasant->getLastOnlineAt())
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="innerTableWidgetHeading"><strong>Activity</strong></div>
                                <div class="innerTableWidgetBody">
                                    <strong>Last active at</strong> {!! $peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                    ({!! $peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->diffForHumans() !!})<br>
                                </div>
                            </div>
                        @endif

                        @if(count($peasant->completedPayments) > 0)
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="innerTableWidgetHeading"><strong>Payments</strong></div>
                                <div class="innerTableWidgetBody">
                                    <strong># of payments</strong>: {{ count($peasant->completedPayments) }} <br>
                                    <strong>Last Payment amount</strong>: &euro;{{ number_format($peasant->completedPayments[0]->amount/ 100, 2) }} <br>
                                    <strong>Last Payment date</strong>: {{ $peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                    ({!! $peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                    @if(count($peasant->completedPayments) > 1)
                                        <strong>Previous Payment amount</strong>: &euro;{{ number_format($peasant->completedPayments[1]->amount/ 100, 2) }} <br>
                                        <strong>Previous Payment date</strong>: {{ $peasant->completedPayments[1]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                        ({!! $peasant->completedPayments[1]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>
                                    @endif

                                    <?php
                                    $moneySpent = 0;
                                    foreach ($peasant->completedPayments as $payment) {
                                        $moneySpent += $payment->amount;
                                    }
                                    ?>

                                    <strong>Money spent</strong>: &euro;{{ number_format($moneySpent/ 100, 2) }} <br>
                                </div>
                            </div>
                        @endif

                        @if($peasant->getDeactivatedAt())
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="innerTableWidgetHeading"><strong>Deactivation info</strong></div>
                                <div class="innerTableWidgetBody deactivationInfo">

                                    <strong>Deactivated at</strong> {!! $peasant->getDeactivatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                    ({!! $peasant->getDeactivatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                    <strong>Remained active for</strong> {!! $peasant->getCreatedAt()->tz('Europe/Amsterdam')->shortAbsoluteDiffForHumans($peasant->getDeactivatedAt()->tz('Europe/Amsterdam')) !!}<br>
                                </div>
                            </div>
                        @endif

                        @if($peasant->affiliateTracking)
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="innerTableWidgetHeading"><strong>Affiliate tracking</strong></div>
                                <div class="innerTableWidgetBody">
                                    <strong>Affiliate</strong> {{ $peasant->affiliateTracking->getAffiliate() }}<br>
                                    <strong>Click ID</strong> {{ $peasant->affiliateTracking->getClickId() }}<br>
                                    <strong>Media ID</strong> {{ $peasant->affiliateTracking->getMediaId() }}<br>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   required
                                   value="{!! $peasant->username !!}"
                            >
                            @if ($errors->has('username'))
                                {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="username">{{ trans('user_constants.email') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="{!! $peasant->email !!}"
                            >
                            @if ($errors->has('email'))
                                {!! $errors->first('email', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">Active</label>
                            <select name="active"
                                    id="active"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! ($peasant->active == 1) ? 'selected' : '' !!}>Active</option>
                                <option value="0" {!! ($peasant->active == 0) ? 'selected' : '' !!}>Inactive</option>
                            </select>
                            @if ($errors->has('active'))
                                {!! $errors->first('active', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Date of birth:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text"
                                       class="form-control pull-right datepicker__date"
                                       name="dob"
                                       value="{{ $peasant->meta->dob ? $peasant->meta->dob->format('d-m-Y') : '' }}"
                                >
                                @if ($errors->has('dob'))
                                    {!! $errors->first('dob', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">City</label>
                            <input type="text"
                                   class="JS--autoCompleteCites form-control"
                                   name="city"
                                   value="{!! ucfirst($peasant->meta->city) !!}"
                            >
                            @if ($errors->has('city'))
                                {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Country</label>
                            <input type="text"
                                   class="form-control"
                                   name="country"
                                   value="{!! $peasant->meta->country !!}"
                            >
                            @if ($errors->has('country'))
                                {!! $errors->first('country', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::selectableFields('peasant') as $field => $possibleOptions)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <select name="{!! $field !!}"
                                        id="{!! $field !!}"
                                        class="form-control"
                                >
                                    <option value=""
                                            {!! old($field) == '' ? 'selected' : '' !!}
                                    ></option>
                                    @foreach($possibleOptions as $key => $value)
                                        <option value="{!! $key == '' ? null : $key !!}"
                                                {!! ($peasant->meta[$field] === $key) ? 'selected' : '' !!}
                                        >
                                            {!! $value !!}
                                        </option>
                                    @endforeach
                                </select>
                                @include('helpers.forms.error_message', ['field' => $field])
                            </div>
                        </div>
                        {{--Prevents breaking when error on > xs viewports--}}
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <?php $counter = 0; ?>
                    @foreach(\UserConstants::textFields('peasant') as $field)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <textarea name="{!! $field !!}"
                                          id="{!! $field !!}"
                                          class="form-control"
                                          cols="30"
                                          rows="10"
                                >{!! $peasant->meta[$field] !!}</textarea>
                                @include('helpers.forms.error_message', ['field' => $field])
                            </div>
                        </div>
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label
                                for="email_notification_settings">{{ @trans('edit_profile.email_notification_settings') }}</label>

                            @foreach($availableEmailTypes as $emailType)
                                <div class="checkbox notificationSettingsItem">
                                    <label for="emailType{{ $emailType->id }}">
                                        <input
                                            id="emailType{{ $emailType->id }}"
                                            type="checkbox"
                                            value="{{ $emailType->id }}"
                                            name="email_notifications[]"
                                            {{ in_array($emailType->id, $userEmailTypeIds) ? 'checked' : '' }}
                                        >
                                        {{ @trans('edit_profile.user_email_types.' . $emailType->name) }}
                                    </label>
                                    @if($emailType->id !== \App\EmailType::GENERAL)
                                        <div class="helpText">
                                            {{ @trans('edit_profile.user_email_types.' . $emailType->name . '_help') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_images">Gallery Images</label>
                            <input type="file" class="form-control" id="user_images" name="user_images[]" multiple>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive" id="images-section">
            <table class="table table-striped">
                <?php $tableColumnAmount = 3; ?>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Visible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="<?= $tableColumnAmount; ?>">
                        Profile Image
                    </td>
                </tr>
                @if($peasant->hasProfileImage())
                    <tr>
                        <td>
                            <img width="200" src="{!! \StorageHelper::profileImageUrl($peasant) !!}"/>
                        </td>
                        <td>
                            <?= ($peasant->profileImage->visible) ? 'Yes' : 'No' ; ?>
                        </td>
                        <td class="action-buttons">
                            <form method="POST" action="{!! route('images.destroy', ['imageId' => $peasant->profileImage->id]) !!}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="<?= $tableColumnAmount; ?>">
                            No profile image set
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="<?= $tableColumnAmount; ?>">
                        Other Images
                    </td>
                </tr>

                <?php $peasantImagesNotProfile = $peasant->imagesNotProfile; ?>
                @if(!is_null($peasantImagesNotProfile))
                    @foreach($peasantImagesNotProfile as $image)
                        <tr>
                            <td>
                                <img width="200" src="{!! \StorageHelper::userImageUrl($peasant->id, $image->filename) !!}"/>
                            </td>
                            <td>
                                <?= ($image->visible) ? 'Yes' : 'No' ; ?>
                            </td>
                            <td class="action-buttons">
                                <form method="POST" action="{!! route('images.destroy', ['imageId' => $image->id]) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{!! route('users.set-profile-image', ['userId' => $peasant->id, 'imageId' => $image->id]) !!}" class="btn btn-success">Set profile</a>
                                <a href="{!! route('images.toggle_visibility', ['imageId' => $image->id]) !!}" class="btn btn-default">Toggle visibility</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="<?= $tableColumnAmount; ?>">
                            No images found
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
