@extends('admin.layouts.default.layout')


@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Bot</h3>
        </div>

        <div style="margin-bottom: 20px; padding: 10px 10px 0">
            <a href="{!! route('admin.conversations.bot.get', ['botId' => $bot->getId()]) !!}"
               class="btn btn-default">Conversations
                <b>({{ $bot->conversations_as_user_a_count + $bot->conversations_as_user_b_count }})</b></a>

            <a href="{!! route('admin.messages.bot', ['botId' => $bot->getId()]) !!}"
               class="btn btn-default">Messages sent/received
                <b>({{ $bot->messaged_count + $bot->messages_count }})</b></a>

            <a href="{!! route('admin.bot-messages.bot.get', ['botId' => $bot->getId()]) !!}"
               class="btn btn-default">Bot messages assigned
                <b>({{ $bot->bot_messages_count }})</b></a>
            <a href="{!! route('admin.bots.message-with-bot.get', ['botId' =>  $bot->id, 'onlyOnlinePeasants' => '0']) !!}" class="btn btn-default">Message peasant with bot</a>
            <a href="{!! route('admin.bots.message-with-bot.get', ['botId' => $bot->id, 'onlyOnlinePeasants' => '1']) !!}" class="btn btn-default">Message online peasant with bot</a>

            <form
                method="POST"
                action="{!! route('admin.bots.destroy', ['id' => $bot->id]) !!}"
                style="display: inline-block"
            >
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <button type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this bot?')">
                    Delete
                </button>
            </form>
        </div>

        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.bots.update', ['id' => $bot->id]) !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="userStats botStats">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages received</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $bot->messaged_count }} <br>
                                <strong>Last month:</strong> {{ $bot->messaged_last_month_count }} <br>
                                <strong>This month:</strong> {{ $bot->messaged_this_month_count }} <br>
                                <strong>Last Week:</strong> {{ $bot->messaged_last_week_count }} <br>
                                <strong>This week:</strong> {{ $bot->messaged_this_week_count }} <br>
                                <strong>Yesterday:</strong> {{ $bot->messaged_yesterday_count }} <br>
                                <strong>Today:</strong> {{ $bot->messaged_today_count }} <br>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages sent</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $bot->messages_count }} <br>
                                <strong>Last month:</strong> {{ $bot->messages_last_month_count }} <br>
                                <strong>This month:</strong> {{ $bot->messages_this_month_count }} <br>
                                <strong>Last Week:</strong> {{ $bot->messages_last_week_count }} <br>
                                <strong>This week:</strong> {{ $bot->messages_this_week_count }} <br>
                                <strong>Yesterday:</strong> {{ $bot->messages_yesterday_count }} <br>
                                <strong>Today:</strong> {{ $bot->messages_today_count }} <br>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Views</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $bot->views->count()  }} <br>
                                <strong>Unique:</strong> {{ $bot->uniqueViews()->get()->count() }}
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages received/sent ratio (larger is better)</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {{ $bot->messagedVsMessagesPercentage() }} ({{ $bot->messaged_count }} / {{ $bot->messages_count }}) <br>
                                <strong>Last month:</strong> {{ $bot->messagedVsMessagesPercentageLastMonth() }} ({{ $bot->messaged_last_month_count }} / {{ $bot->messages_last_month_count }}) <br>
                                <strong>This month:</strong> {{ $bot->messagedVsMessagesPercentageThisMonth() }} ({{ $bot->messaged_this_month_count }} / {{ $bot->messages_this_month_count }}) <br>
                                <strong>Last Week:</strong> {{ $bot->messagedVsMessagesPercentageLastWeek() }} ({{ $bot->messaged_last_week_count }} / {{ $bot->messages_last_week_count }}) <br>
                                <strong>This week:</strong> {{ $bot->messagedVsMessagesPercentageThisWeek() }} ({{ $bot->messaged_this_week_count }} / {{ $bot->messages_this_week_count }}) <br>
                                <strong>Yesterday:</strong> {{ $bot->messagedVsMessagesPercentageYesterday() }} ({{ $bot->messaged_yesterday_count }} / {{ $bot->messages_yesterday_count }}) <br>
                                <strong>Today:</strong> {{ $bot->messagedVsMessagesPercentageToday() }} ({{ $bot->messaged_today_count }} / {{ $bot->messages_today_count }}) <br>
                            </div>
                        </div>
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
                                   value="{!! $bot->username !!}"
                            >
                            @if ($errors->has('username'))
                                {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">

                        @if(\Auth::user()->isAdmin())
                            <div class="form-group">
                                <label for="active">Active</label>
                                <select name="active"
                                        id="active"
                                        class="form-control"
                                        required
                                >
                                    <option value="1" {!! ($bot->active == 1) ? 'selected' : '' !!}>Active</option>
                                    <option value="0" {!! ($bot->active == 0) ? 'selected' : '' !!}>Inactive</option>
                                </select>
                                @if ($errors->has('active'))
                                    {!! $errors->first('active', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label for="active">Active</label>
                                <select name="active"
                                        id="active"
                                        class="form-control"
                                        required
                                >
                                    @if($bot->active == 1)
                                        <option value="1" {!! ($bot->active == 1) ? 'selected' : '' !!}>Active</option>
                                    @else
                                        <option value="0" {!! ($bot->active == 0) ? 'selected' : '' !!}>Inactive</option>
                                    @endif
                                </select>
                                @if ($errors->has('active'))
                                    {!! $errors->first('active', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="too_slutty_for_ads">Too slutty for ads</label>

                            <select
                                class="form-control"
                                id="too_slutty_for_ads"
                                name="too_slutty_for_ads"
                            >
                                <option value="0" {{ $bot->meta->getTooSluttyForAds() == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $bot->meta->getTooSluttyForAds() == 1 ? 'selected' : '' }}>Yes</option>
                            </select>

                            @if ($errors->has('too_slutty_for_ads'))
                                {!! $errors->first('too_slutty_for_ads', '<small class="form-error">:message</small>') !!}
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
                                       value="{{ $bot->meta->dob ? $bot->meta->dob->format('d-m-Y') : '' }}"
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
                                   value="{!! ucfirst($bot->meta->city) !!}"
                            >
                            @if ($errors->has('city'))
                                {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="country">Country</label>

                            <select
                                class="form-control"
                                id="country"
                                name="country"
                            >
                                <option value="nl" {{ $bot->meta->country === 'nl' ? 'selected' : '' }}>Netherlands</option>
                                <option value="be" {{ $bot->meta->country === 'be' ? 'selected' : '' }}>Belgium</option>
                            </select>

                            @if ($errors->has('country'))
                                {!! $errors->first('country', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                  <?php $counter = 0; ?>
                    @foreach(\UserConstants::selectableFields() as $field => $possibleOptions)
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
                                                {!! ($bot->meta->{$field} === $key) ? 'selected' : '' !!}
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
                    @foreach(\UserConstants::textFields() as $field)
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="{!! $field !!}">{!! ucfirst(str_replace('_', ' ', $field)) !!}</label>
                                <textarea name="{!! $field !!}"
                                          id="{!! $field !!}"
                                          class="form-control"
                                          cols="30"
                                          rows="10"
                                >{!! $bot->meta[$field] !!}</textarea>
                                @include('helpers.forms.error_message', ['field' => $field])
                            </div>
                        </div>
                        @if($counter % 2)
                            <div class="col-xs-12"></div>
                        @endif
                        <?php $counter++; ?>
                    @endforeach

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
                @if($bot->hasProfileImage())
                    <tr>
                        <td>
                            <img width="200" src="{!! \StorageHelper::profileImageUrl($bot) !!}"/>
                        </td>
                        <td>
                            <?= ($bot->profileImage->visible) ? 'Yes' : 'No' ; ?>
                        </td>
                        <td class="action-buttons">
                            <form method="POST" action="{!! route('images.destroy', ['imageId' => $bot->profileImage->id]) !!}">
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

                <?php $botImagesNotProfile = $bot->imagesNotProfile; ?>
                @if(!is_null($botImagesNotProfile))
                    @foreach($botImagesNotProfile as $image)
                        <tr>
                            <td>
                                <img width="200" src="{!! \StorageHelper::userImageUrl($bot->id, $image->filename) !!}"/>
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
                                <a href="{!! route('users.set-profile-image', ['userId' => $bot->id, 'imageId' => $image->id]) !!}" class="btn btn-success">Set profile</a>
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
