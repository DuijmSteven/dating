
@extends('admin.layouts.default.layout')



@section('content')

    <div class="row">

        @include('admin.users-search')

        <div class="col-xs-12">
            <div class="pagination__container text-right">
                {!! $conversions->render() !!}
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Peasants (Total: <strong>{!! $conversions->total() !!}</strong>)</h3>
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
                        @foreach($conversions as $conversion)
                            <tr>
                                <td class="no-wrap">
                                    <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $conversion->peasant->getId()]) !!}">
                                        <img
                                            style="object-fit: cover; width: 70px; height: 70px"
                                            src="{!! \StorageHelper::profileImageUrl($conversion->peasant, true) !!}"
                                            alt=""
                                        >
                                    </a>

                                    <div class="innerTableWidgetHeading"><strong>User Data</strong></div>
                                    <div class="innerTableWidgetBody">
                                        <strong>ID</strong>:
                                            <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $conversion->peasant->getId()]) !!}">
                                                {!! $conversion->peasant->getId() !!}
                                            </a>
                                        <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.username') !!}:</strong> {!! $conversion->peasant->username !!} <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.email') !!}:</strong> {!! $conversion->peasant->email !!} <br>
                                        <strong>Country code:</strong> {!! $conversion->peasant->meta->country !!} <br>
                                        <strong>Email verified:</strong> {!! \App\UserMeta::emailVerifiedDescriptionPerId()[$conversion->peasant->meta->getEmailVerified()] !!} <br>

                                        @php
                                            $highlightTypeClass = '';

                                            if ($conversion->peasant->account->getCredits() > 10) {
                                                $highlightTypeClass = 'success';
                                            } else if ($conversion->peasant->account->getCredits() > 4) {
                                                $highlightTypeClass = 'warning';
                                            } else {
                                                $highlightTypeClass = 'error';
                                            }
                                        @endphp


                                        <strong>Credits</strong>: <span class="highlightAsDisk {{ $highlightTypeClass }}">{{ $conversion->peasant->account->getCredits() }}</span> <br>
                                        <strong>{!! trans(config('app.directory_name') . '/user_constants.age') !!}</strong> {!! $carbonNow->diffInYears($conversion->peasant->meta->dob) !!} <br>
                                        @foreach(\UserConstants::selectableFields('peasant') as $fieldName => $a)
                                            @if(isset($conversion->peasant->meta->{$fieldName}))
                                                <strong>{!! ucfirst(str_replace('_', ' ', $fieldName)) !!}:
                                                </strong> {!! trans(config('app.directory_name') . '/user_constants.' . $fieldName . '.' . $conversion->peasant->meta->{$fieldName}) !!} <br>
                                            @endif
                                        @endforeach

                                        @foreach(array_merge(\UserConstants::textFields('peasant'), \UserConstants::textInputs('peasant')) as $fieldName)
                                            @if(isset($conversion->peasant->meta->{$fieldName}) && $conversion->peasant->meta->{$fieldName} != '')
                                                <div style="max-width: 250px; {!! $fieldName === 'about_me' ? 'white-space: normal' : '' !!}">
                                                    <strong>{!! trans(config('app.directory_name') . '/user_constants.' . $fieldName) !!}:</strong>

                                                    @if($fieldName === 'about_me')
                                                        {{ substr($conversion->peasant->meta->{$fieldName}, 0, 40) }}{{ strlen($conversion->peasant->meta->{$fieldName}) > 41 ? '...' : '' }}
                                                    @elseif($fieldName === 'dob')
                                                        {{ $conversion->peasant->meta->{$fieldName}->format('d-m-Y') }}
                                                    @else
                                                        {{ $conversion->peasant->meta->{$fieldName} }}
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @if($conversion->peasant->getLastOnlineAt() || $conversion->peasant->getCreatedAt())
                                        <div class="innerTableWidgetHeading"><strong>Activity</strong></div>
                                        <div class="innerTableWidgetBody">

                                            @if($conversion->peasant->getLastOnlineAt())
                                                <strong>Last active at</strong> {!! $conversion->peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                                                    ({!! $conversion->peasant->getLastOnlineAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                                    <br>
                                            @endif
                                                <strong>Created at</strong> {!! $conversion->peasant->getCreatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                                                            ({!! $conversion->peasant->getCreatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})
                                                <br>
                                        </div>
                                    @endif

                                    @if(count($conversion->peasant->completedPayments) > 0)
                                        <div class="innerTableWidgetHeading"><strong>Payments</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong># of payments</strong>: {{ count($conversion->peasant->completedPayments) }} <br>
                                            <strong>Latest Payment amount</strong>: &euro;{{ number_format($conversion->peasant->completedPayments[0]->amount/ 100, 2) }} <br>
                                            <strong>Latest Payment date</strong>: {{ $conversion->peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                                                                ({!! $conversion->peasant->completedPayments[0]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                            @if(count($conversion->peasant->completedPayments) > 1)
                                                <strong>Previous Payment amount</strong>: &euro;{{ number_format($conversion->peasant->completedPayments[1]->amount/ 100, 2) }} <br>
                                                <strong>Previous Payment date</strong>: {{ $conversion->peasant->completedPayments[1]->created_at->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') }}
                                                ({!! $conversion->peasant->completedPayments[1]->created_at->tz('Europe/Amsterdam')->diffForHumans() !!})<br>
                                            @endif

                                            <?php
                                            $moneySpent = 0;
                                            foreach ($conversion->peasant->completedPayments as $payment) {
                                                $moneySpent += $payment->amount;
                                            }
                                            ?>

                                            <strong>Money spent</strong>: &euro;{{ number_format($moneySpent/ 100, 2) }} <br>
                                        </div>
                                    @endif

                                    @if($conversion->peasant->getDeactivatedAt())
                                        <div class="innerTableWidgetHeading"><strong>Deactivation info</strong></div>
                                        <div class="innerTableWidgetBody deactivationInfo">

                                            <strong>Deactivated at</strong> {!! $conversion->peasant->getDeactivatedAt()->tz('Europe/Amsterdam')->format('d-m-Y H:i:s') !!}
                                            ({!! $conversion->peasant->getDeactivatedAt()->tz('Europe/Amsterdam')->diffForHumans() !!})<br>

                                            <strong>Remained active for</strong> {!! $conversion->peasant->getCreatedAt()->tz('Europe/Amsterdam')->shortAbsoluteDiffForHumans($conversion->peasant->getDeactivatedAt()->tz('Europe/Amsterdam')) !!}<br>
                                        </div>
                                    @endif

                                    @if($conversion->peasant->affiliateTracking)
                                        <div class="innerTableWidgetHeading"><strong>Affiliate tracking</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong>Affiliate</strong> {{ $conversion->peasant->affiliateTracking->getAffiliate() }}<br>
                                            <strong>Publisher</strong> {{ $conversion->peasant->affiliateTracking->getPublisher() ? \App\UserAffiliateTracking::publisherDescriptionPerId()[$conversion->peasant->affiliateTracking->getPublisher()] : 'Not available' }}<br>
                                            <strong>Click ID</strong> {{ $conversion->peasant->affiliateTracking->getClickId() }}<br>

{{--                                            <strong>Lead eligibility</strong>--}}
{{--                                                <span>--}}
{{--                                                    {{ \App\UserAffiliateTracking::eligibilityDescriptionPerId()[$conversion->peasant->affiliateTracking->getLeadEligibility()] }}--}}
{{--                                                </span>--}}
{{--                                            <br>--}}

{{--                                            <strong>Lead validation status</strong>--}}
{{--                                                <span>--}}
{{--                                                    {{ \App\UserAffiliateTracking::statusDescriptionPerId()[$conversion->peasant->affiliateTracking->getLeadStatus()] }}--}}
{{--                                                </span>--}}
{{--                                            <br>--}}

                                            @if($conversion->peasant->affiliateTracking->getMediaId())
                                                <strong>Media ID</strong> {{ $conversion->peasant->affiliateTracking->getMediaId() }}
                                            @endif
                                            <br>
                                        </div>
                                    @endif

                                    @if($conversion->peasant->meta->getRegistrationKeyword())
                                        <div class="innerTableWidgetHeading"><strong>Lead data</strong></div>
                                        <div class="innerTableWidgetBody">
                                            <strong>Ad keyword</strong> {{ $conversion->peasant->meta->getRegistrationKeyword() }}<br>
                                    </div>
                                    @endif

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
                                    <a href="{!! route('admin.peasants.edit.get', ['peasantId' => $conversion->peasant->getId()]) !!}" class="btn btn-default">Edit</a>
                                    <a href="{!! route('admin.conversations.peasant.get', ['peasantId' => $conversion->peasant->getId()]) !!}" class="btn btn-default">Conversations <b>({{ count($conversion->peasant->conversationsAsUserA) + count($conversion->peasant->conversationsAsUserB) }})</b></a>
                                    <a href="{!! route('admin.messages.peasant', ['peasantId' => $conversion->peasant->getId()]) !!}" class="btn btn-default">Messages <b>({{ $conversion->peasant->messages_count +  $conversion->peasant->messaged_count}})</b></a>
                                    <a href="{!! route('admin.payments.peasant.overview', ['peasantId' => $conversion->peasant->getId()]) !!}" class="btn btn-default">Payments <b>({{ count($conversion->peasant->payments) }})</b></a>
                                    <a href="{!! route('admin.peasants.message-as-bot.get', ['peasantId' => $conversion->peasant->getId(), 'onlyOnlineBots' => '0']) !!}" class="btn btn-default">Message user as bot</a>
                                    <a href="{!! route('admin.peasants.message-as-bot.get', [ 'peasantId' => $conversion->peasant->getId(), 'onlyOnlineBots' => '1']) !!}" class="btn btn-default">Message user as online bot</a>


                                    @if(
                                        $conversion->peasant->affiliateTracking &&
                                        $conversion->peasant->affiliateTracking->affiliate === \App\UserAffiliateTracking::AFFILIATE_XPARTNERS &&
                                        $conversion->peasant->affiliateTracking->getLeadStatus() === \App\UserAffiliateTracking::LEAD_STATUS_UNVALIDATED
                                    )
                                        <a href="{!! route('admin.peasants.validate-xpartners-lead', ['peasantId' => $conversion->peasant->getId()]) !!}" class="btn btn-default">Validate lead</a>
                                    @endif


                                    <form method="POST" action="{!! route('admin.users.destroy', ['userId' => $conversion->peasant->getId()]) !!}">
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
                {!! $conversions->render() !!}
            </div>
        </div>

    </div>

@endsection
