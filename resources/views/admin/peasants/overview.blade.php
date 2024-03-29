
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        @include('admin.users-search')

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $peasants->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Peasants (Total: <strong>{!! $peasants->total() !!}</strong>)</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>User Data & Payments</th>
                            <th>Stats</th>
                            <th>Graphs</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($peasants as $peasant)
                            <tr>
                                <td class="no-wrap">
                                    <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $peasant->getId()]) !!}">
                                        <img
                                            style="object-fit: cover; width: 70px; height: 70px"
                                            src="{!! \StorageHelper::profileImageUrl($peasant, true) !!}"
                                            alt=""
                                        >
                                    </a>

                                    <div class="innerTableWidgetHeading"><strong>User Data</strong></div>
                                    <div class="innerTableWidgetBody">
                                        <strong>ID</strong>:
                                            <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $peasant->getId()]) !!}">
                                                {!! $peasant->getId() !!}
                                            </a>
                                        <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.username') !!}:</strong> {!! $peasant->username !!} <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.email') !!}:</strong> {!! $peasant->email !!} <br>
                                        <strong>Country code:</strong> {!! $peasant->meta->country !!} <br>
                                        <strong>Email verified:</strong> {!! \App\UserMeta::emailVerifiedDescriptionPerId()[$peasant->meta->getEmailVerified()] !!} <br>

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


                                        <strong>Credits</strong>: <span class="highlightAsDisk {{ $highlightTypeClass }}">{{ $peasant->account->getCredits() }}</span> <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($peasant->meta->dob) !!} <br>
                                        @foreach(\UserConstants::selectableFields('peasant') as $fieldName => $a)
                                            @if(isset($peasant->meta->{$fieldName}))
                                                <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                                </strong> {!! trans(config('app.directory_name') . '/user_constants.' . $fieldName . '.' . $peasant->meta->{$fieldName}) !!} <br>
                                            @endif
                                        @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('peasant'), \UserConstants::textInputs('peasant')) as $fieldName)
                                            @if(isset($peasant->meta->{$fieldName}) && $peasant->meta->{$fieldName} != '')
                                                <div style="max-width: 250px; {!! $fieldName === 'about_me' ? 'white-space: normal' : '' !!}">
                                                    <strong>{!! trans(config('app.directory_name') . '/user_constants.' . $fieldName) !!}:</strong>

                                                    @if($fieldName === 'about_me')
                                                        {{ substr($peasant->meta->{$fieldName}, 0, 40) }}{{ strlen($peasant->meta->{$fieldName}) > 41 ? '...' : '' }}
                                                    @elseif($fieldName === 'dob')
                                                        {{ $peasant->meta->{$fieldName}->format('d-m-Y') }}
                                                    @else
                                                        {{ $peasant->meta->{$fieldName} }}
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @if($peasant->getLastOnlineAt() || $peasant->getCreatedAt())
                                        <div class="innerTableWidgetHeading"><strong>Activity</strong></div>
                                        <div class="innerTableWidgetBody">

                                            @if($peasant->getLastOnlineAt())
                                                <strong>Last active at</strong> {!! $peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                                                    ({!! $peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                                    <br>
                                            @endif
                                                <strong>Created at</strong> {!! $peasant->getCreatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                                            ({!! $peasant->getCreatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                                <br>
                                        </div>
                                    @endif

                                    @if(count($peasant->completedPayments) > 0)
                                        <div class="innerTableWidgetHeading"><strong>Payments</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong># of payments</strong>: {{ count($peasant->completedPayments) }} <br>
                                            <strong>Latest Payment amount</strong>: &euro;{{ number_format($peasant->completedPayments[0]->amount/ 100, 2) }} <br>
                                            <strong>Latest Payment date</strong>: {{ $peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
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
                                    @endif

                                    @if($peasant->getDeactivatedAt())
                                        <div class="innerTableWidgetHeading"><strong>Deactivation info</strong></div>
                                        <div class="innerTableWidgetBody deactivationInfo">

                                            <strong>Deactivated at</strong> {!! $peasant->getDeactivatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                            ({!! $peasant->getDeactivatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                            <strong>Remained active for</strong> {!! $peasant->getCreatedAt()->tz('Europe/Amsterdam')->shortAbsoluteDiffForHumans($peasant->getDeactivatedAt()->tz('Europe/Amsterdam')) !!}<br>
                                        </div>
                                    @endif

                                    @if($peasant->affiliateTracking)
                                        <div class="innerTableWidgetHeading"><strong>Affiliate tracking</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong>Affiliate</strong> {{ $peasant->affiliateTracking->getAffiliate() }}<br>
                                            <strong>Publisher</strong> {{ $peasant->affiliateTracking->getPublisher() ? \App\UserAffiliateTracking::publisherDescriptionPerId()[$peasant->affiliateTracking->getPublisher()] : 'Not available' }}<br>
                                            <strong>Click ID</strong> {{ $peasant->affiliateTracking->getClickId() }}<br>

{{--                                            <strong>Lead eligibility</strong>--}}
{{--                                                <span>--}}
{{--                                                    {{ \App\UserAffiliateTracking::eligibilityDescriptionPerId()[$peasant->affiliateTracking->getLeadEligibility()] }}--}}
{{--                                                </span>--}}
{{--                                            <br>--}}

{{--                                            <strong>Lead validation status</strong>--}}
{{--                                                <span>--}}
{{--                                                    {{ \App\UserAffiliateTracking::statusDescriptionPerId()[$peasant->affiliateTracking->getLeadStatus()] }}--}}
{{--                                                </span>--}}
{{--                                            <br>--}}

                                            @if($peasant->affiliateTracking->getMediaId())
                                                <strong>Media ID</strong> {{ $peasant->affiliateTracking->getMediaId() }}
                                            @endif
                                            <br>
                                        </div>
                                    @endif

                                    @if($peasant->meta->getRegistrationKeyword())
                                        <div class="innerTableWidgetHeading"><strong>Lead data</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong>Ad keyword</strong> {{ $peasant->meta->getRegistrationKeyword() }}<br>
                                    </div>
                                    @endif

                                </td>
                                <td class="no-wrap">
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

                                    <h5 class="statsHeading"><strong>Bot views</strong></h5>
                                    <div class="statsBody">
                                        <strong>All time:</strong> {{ $peasant->hasViewed->count() }} <br>
                                        <strong>Unique:</strong> {{ $peasant->hasViewed->unique('viewed_id')->count() }}
                                    </div>
                                </td>
                                <td>
                                    @if($peasantMessagesCharts['daily'][$loop->index])
                                        <div style="width: 100%">
                                            {!! $peasantMessagesCharts['daily'][$loop->index]->container() !!}
                                            {!! $peasantMessagesCharts['daily'][$loop->index]->script() !!}
                                        </div>
                                    @endif

                                    @if($peasantMessagesCharts['monthly'][$loop->index])
                                        <div style="width: 100%">
                                            {!! $peasantMessagesCharts['monthly'][$loop->index]->container() !!}
                                            {!! $peasantMessagesCharts['monthly'][$loop->index]->script() !!}
                                        </div>
                                    @endif
                                </td>
                                <td class="action-buttons">
                                    <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Edit</a>
                                    <a href="{!! route('admin.conversations.peasant.get', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Conversations <b>({{ $peasant->conversations_as_user_a_count + $peasant->conversations_as_user_b_count }})</b></a>
                                    <a href="{!! route('admin.messages.peasant', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Messages <b>({{ $peasant->messages_count +  $peasant->messaged_count}})</b></a>
                                    <a href="{!! route('admin.payments.peasant.overview', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Payments <b>({{ $peasant->payments_count}})</b></a>
                                    <a href="{!! route('admin.peasants.message-as-bot.get', ['peasantId' => $peasant->getId(), 'onlyOnlineBots' => '0']) !!}" class="btn btn-default">Message user as bot</a>
                                    <a href="{!! route('admin.peasants.message-as-bot.get', [ 'peasantId' => $peasant->getId(), 'onlyOnlineBots' => '1']) !!}" class="btn btn-default">Message user as online bot</a>


                                    @if(
                                        $peasant->affiliateTracking &&
                                        $peasant->affiliateTracking->affiliate === \App\UserAffiliateTracking::AFFILIATE_XPARTNERS &&
                                        $peasant->affiliateTracking->getLeadStatus() === \App\UserAffiliateTracking::LEAD_STATUS_UNVALIDATED
                                    )
                                        <a href="{!! route('admin.peasants.validate-xpartners-lead', ['peasantId' => $peasant->getId()]) !!}" class="btn btn-default">Validate lead</a>
                                    @endif


                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $peasant->getId()]) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this peasant?')">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $peasants->render() !!}
            </div>
        </div>

    </div>

@endsection
