@if(count($users) == 0)
    <div>
        <p style="margin-bottom: 50px">
            {{ trans('profile_grid.no_matching_users') }}
        </p>
    </div>
@endif
@foreach($users as $user)
    <div class="col-sm-12 col-md-4">
        @include('frontend.components.user-summary', ['user' => $user])
    </div>
@endforeach