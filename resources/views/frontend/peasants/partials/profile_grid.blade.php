<?php $count = 0; ?>
@foreach($users as $user)
    <div class="col-sm-12 col-md-4">
        @include('frontend.components.user-summary')
    </div>
{{--    <?php $count++; ?>
    @if($count % 3 == 0)
        <div class="col-xs-12">
        </div>
    @endif--}}
@endforeach