
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        @include('admin.users-search')

        @foreach($fingerprintsWithPeasants as $fingerprint)

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Peasants with fingerprint {{ $fingerprint[0]->getFingerPrint() }} (Total: <strong>{!! $fingerprint->count() !!}</strong>)</h3>
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
                            @foreach($fingerprint as $fingerprint2)
                                <tr>
                                    <td class="no-wrap">
                                        <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $fingerprint2->user->getId()]) !!}">
                                            <img
                                                style="object-fit: cover; width: 70px; height: 70px"
                                                src="{!! \StorageHelper::profileImageUrl($fingerprint2->user, true) !!}"
                                                alt=""
                                            >
                                        </a>

                                        <div class="innerTableWidgetHeading"><strong>User Data</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong>ID</strong>:
                                                <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $fingerprint2->user->getId()]) !!}">
                                                    {!! $fingerprint2->user->getId() !!}
                                                </a>
                                            <br>
                                            <strong>{!! trans(config('app.directory_name') . '/user_constants.username') !!}:</strong> {!! $fingerprint2->user->username !!} <br>
                                            <strong>{!! trans(config('app.directory_name') . '/user_constants.email') !!}:</strong> {!! $fingerprint2->user->email !!} <br>
                                            <strong>Email verified:</strong> {!! \App\UserMeta::emailVerifiedDescriptionPerId()[$fingerprint2->user->meta->getEmailVerified()] !!} <br>

                                            @php
                                                $highlightTypeClass = '';

                                                if ($fingerprint2->user->account->getCredits() > 10) {
                                                    $highlightTypeClass = 'success';
                                                } else if ($fingerprint2->user->account->getCredits() > 4) {
                                                    $highlightTypeClass = 'warning';
                                                } else {
                                                    $highlightTypeClass = 'error';
                                                }
                                            @endphp


                                            <strong>Credits</strong>: <span class="highlightAsDisk {{ $highlightTypeClass }}">{{ $fingerprint2->user->account->getCredits() }}</span> <br>
                                            <strong>{!! trans(config('app.directory_name') . '/user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($fingerprint2->user->meta->dob) !!} <br>
                                            @foreach(\UserConstants::selectableFields('peasant') as $fieldName => $a)
                                                @if(isset($fingerprint2->user->meta->{$fieldName}))
                                                    <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                                    </strong> {!! trans(config('app.directory_name') . '/user_constants.' . $fieldName . '.' . $fingerprint2->user->meta->{$fieldName}) !!} <br>
                                                @endif
                                            @endforeach

                                            @foreach(array_merge(\UserConstants::textFields('peasant'), \UserConstants::textInputs('peasant')) as $fieldName)
                                                @if(isset($fingerprint2->user->meta->{$fieldName}) && $fingerprint2->user->meta->{$fieldName} != '')
                                                    <div style="max-width: 250px; {!! $fieldName === 'about_me' ? 'white-space: normal' : '' !!}">
                                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.' . $fieldName) !!}:</strong>

                                                        @if($fieldName === 'about_me')
                                                            {{ substr($fingerprint2->user->meta->{$fieldName}, 0, 40) }}{{ strlen($fingerprint2->user->meta->{$fieldName}) > 41 ? '...' : '' }}
                                                        @elseif($fieldName === 'dob')
                                                            {{ $fingerprint2->user->meta->{$fieldName}->format('d-m-Y') }}
                                                        @else
                                                            {{ $fingerprint2->user->meta->{$fieldName} }}
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        @if($fingerprint2->user->getLastOnlineAt() || $fingerprint2->user->getCreatedAt())
                                            <div class="innerTableWidgetHeading"><strong>Activity</strong></div>
                                            <div class="innerTableWidgetBody">
                                                @if($fingerprint2->user->getLastOnlineAt())

                                                    <strong>Last active at</strong> {!! $fingerprint2->user->getLastOnlineAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                                                        ({!! $fingerprint2->user->getLastOnlineAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                                        <br>
                                                @endif

                                                    <strong>Created at</strong> {!! $fingerprint2->user->getCreatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                                                ({!! $fingerprint2->user->getCreatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                                    <br>
                                            </div>
                                        @endif

                                        @if(count($fingerprint2->user->completedPayments) > 0)
                                            <div class="innerTableWidgetHeading"><strong>Payments</strong></div>
                                            <div class="innerTableWidgetBody">
                                                <strong># of payments</strong>: {{ count($fingerprint2->user->completedPayments) }} <br>
                                                <strong>Latest Payment amount</strong>: &euro;{{ number_format($fingerprint2->user->completedPayments[0]->amount/ 100, 2) }} <br>
                                                <strong>Latest Payment date</strong>: {{ $fingerprint2->user->completedPayments[0]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                                                                    ({!! $fingerprint2->user->completedPayments[0]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                                @if(count($fingerprint2->user->completedPayments) > 1)
                                                    <strong>Previous Payment amount</strong>: &euro;{{ number_format($fingerprint2->user->completedPayments[1]->amount/ 100, 2) }} <br>
                                                    <strong>Previous Payment date</strong>: {{ $fingerprint2->user->completedPayments[1]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                                    ({!! $fingerprint2->user->completedPayments[1]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>
                                                @endif

                                                <?php
                                                $moneySpent = 0;
                                                foreach ($fingerprint2->user->completedPayments as $payment) {
                                                    $moneySpent += $payment->amount;
                                                }
                                                ?>

                                                <strong>Money spent</strong>: &euro;{{ number_format($moneySpent/ 100, 2) }} <br>
                                            </div>
                                        @endif

                                        @if($fingerprint2->user->getDeactivatedAt())
                                            <div class="innerTableWidgetHeading"><strong>Deactivation info</strong></div>
                                            <div class="innerTableWidgetBody deactivationInfo">

                                                <strong>Deactivated at</strong> {!! $fingerprint2->user->getDeactivatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                ({!! $fingerprint2->user->getDeactivatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                                <strong>Remained active for</strong> {!! $fingerprint2->user->getCreatedAt()->tz('Europe/Amsterdam')->shortAbsoluteDiffForHumans($fingerprint2->user->getDeactivatedAt()->tz('Europe/Amsterdam')) !!}<br>
                                            </div>
                                        @endif

                                        @if($fingerprint2->user->affiliateTracking)
                                            <div class="innerTableWidgetHeading"><strong>Affiliate tracking</strong></div>
                                            <div class="innerTableWidgetBody">
                                                <strong>Affiliate</strong> {{ $fingerprint2->user->affiliateTracking->getAffiliate() }}<br>
                                                <strong>Publisher</strong> {{ $fingerprint2->user->affiliateTracking->getPublisher() ? \App\UserAffiliateTracking::publisherDescriptionPerId()[$fingerprint2->user->affiliateTracking->getPublisher()] : 'Not available' }}<br>
                                                <strong>Click ID</strong> {{ $fingerprint2->user->affiliateTracking->getClickId() }}<br>

                                                <strong>Lead eligibility</strong>
                                                    <span>
                                                        {{ \App\UserAffiliateTracking::eligibilityDescriptionPerId()[$fingerprint2->user->affiliateTracking->getLeadEligibility()] }}
                                                    </span>
                                                <br>

                                                <strong>Lead validation status</strong>
                                                    <span>
                                                        {{ \App\UserAffiliateTracking::statusDescriptionPerId()[$fingerprint2->user->affiliateTracking->getLeadStatus()] }}
                                                    </span>
                                                <br>

                                            @if($fingerprint2->user->affiliateTracking->getMediaId())
                                                    <strong>Media ID</strong> {{ $fingerprint2->user->affiliateTracking->getMediaId() }}
                                                @endif
                                                <br>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="no-wrap">
                                        <h5 class="statsHeading"><strong>Messages received</strong></h5>
                                        <div class="statsBody">
                                            <strong>All time:</strong> {{ $fingerprint2->user->messaged_count }} <br>
                                            <strong>Last month:</strong> {{ $fingerprint2->user->messaged_last_month_count }} <br>
                                            <strong>This month:</strong> {{ $fingerprint2->user->messaged_this_month_count }} <br>
                                            <strong>Last Week:</strong> {{ $fingerprint2->user->messaged_last_week_count }} <br>
                                            <strong>This week:</strong> {{ $fingerprint2->user->messaged_this_week_count }} <br>
                                            <strong>Yesterday:</strong> {{ $fingerprint2->user->messaged_yesterday_count }} <br>
                                            <strong>Today:</strong> {{ $fingerprint2->user->messaged_today_count }} <br>
                                        </div>

                                        <h5 class="statsHeading"><strong>Messages sent</strong></h5>
                                        <div class="statsBody">
                                            <strong>All time:</strong> {{ $fingerprint2->user->messages_count }} <br>
                                            <strong>Last month:</strong> {{ $fingerprint2->user->messages_last_month_count }} <br>
                                            <strong>This month:</strong> {{ $fingerprint2->user->messages_this_month_count }} <br>
                                            <strong>Last Week:</strong> {{ $fingerprint2->user->messages_last_week_count }} <br>
                                            <strong>This week:</strong> {{ $fingerprint2->user->messages_this_week_count }} <br>
                                            <strong>Yesterday:</strong> {{ $fingerprint2->user->messages_yesterday_count }} <br>
                                            <strong>Today:</strong> {{ $fingerprint2->user->messages_today_count }} <br>
                                        </div>

                                        <h5 class="statsHeading"><strong>Messages received/sent ratio (smaller is better)</strong></h5>
                                        <div class="statsBody">
                                            <strong>All time:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentage() }} ({{ $fingerprint2->user->messaged_count }} / {{ $fingerprint2->user->messages_count }}) <br>
                                            <strong>Last month:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentageLastMonth() }} ({{ $fingerprint2->user->messaged_last_month_count }} / {{ $fingerprint2->user->messages_last_month_count }}) <br>
                                            <strong>This month:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentageThisMonth() }} ({{ $fingerprint2->user->messaged_this_month_count }} / {{ $fingerprint2->user->messages_this_month_count }}) <br>
                                            <strong>Last Week:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentageLastWeek() }} ({{ $fingerprint2->user->messaged_last_week_count }} / {{ $fingerprint2->user->messages_last_week_count }}) <br>
                                            <strong>This week:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentageThisWeek() }} ({{ $fingerprint2->user->messaged_this_week_count }} / {{ $fingerprint2->user->messages_this_week_count }}) <br>
                                            <strong>Yesterday:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentageYesterday() }} ({{ $fingerprint2->user->messaged_yesterday_count }} / {{ $fingerprint2->user->messages_yesterday_count }}) <br>
                                            <strong>Today:</strong> {{ $fingerprint2->user->messagedVsMessagesPercentageToday() }} ({{ $fingerprint2->user->messaged_today_count }} / {{ $fingerprint2->user->messages_today_count }}) <br>
                                        </div>

                                        <h5 class="statsHeading"><strong>Bot views</strong></h5>
                                        <div class="statsBody">
                                            <strong>All time:</strong> {{ $fingerprint2->user->hasViewed->count() }} <br>
                                            <strong>Unique:</strong> {{ $fingerprint2->user->hasViewed->unique('viewed_id')->count() }}
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
                                        <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $fingerprint2->user->getId()]) !!}" class="btn btn-default">Edit</a>
                                        <a href="{!! route('admin.conversations.peasant.get', ['peasantId' => $fingerprint2->user->getId()]) !!}" class="btn btn-default">Conversations <b>({{ $fingerprint2->user->conversations_as_user_a_count + $fingerprint2->user->conversations_as_user_b_count }})</b></a>
                                        <a href="{!! route('admin.messages.peasant', ['peasantId' => $fingerprint2->user->getId()]) !!}" class="btn btn-default">Messages <b>({{ $fingerprint2->user->messages_count +  $fingerprint2->user->messaged_count}})</b></a>
                                        <a href="{!! route('admin.payments.peasant.overview', ['peasantId' => $fingerprint2->user->getId()]) !!}" class="btn btn-default">Payments <b>({{ $fingerprint2->user->payments_count}})</b></a>
                                        <a href="{!! route('admin.peasants.message-as-bot.get', ['peasantId' => $fingerprint2->user->getId(), 'onlyOnlineBots' => '0']) !!}" class="btn btn-default">Message user as bot</a>
                                        <a href="{!! route('admin.peasants.message-as-bot.get', [ 'peasantId' => $fingerprint2->user->getId(), 'onlyOnlineBots' => '1']) !!}" class="btn btn-default">Message user as online bot</a>


                                        @if(
                                            $fingerprint2->user->affiliateTracking &&
                                            $fingerprint2->user->affiliateTracking->affiliate === \App\UserAffiliateTracking::AFFILIATE_XPARTNERS &&
                                            $fingerprint2->user->affiliateTracking->getLeadStatus() === \App\UserAffiliateTracking::LEAD_STATUS_UNVALIDATED
                                        )
                                            <a href="{!! route('admin.peasants.validate-xpartners-lead', ['peasantId' => $fingerprint2->user->getId()]) !!}" class="btn btn-default">Validate lead</a>
                                        @endif


                                        <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $fingerprint2->user->getId()]) !!}">
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

        @endforeach
    </div>

@endsection
