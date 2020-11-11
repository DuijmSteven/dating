@if(count($users) == 0)
    <div>
        <p style="margin-bottom: 1300px">
            {{ trans(config('app.directory_name') . '/profile_grid.no_matching_users') }}
        </p>
    </div>
@endif
@foreach($users as $user)
    <div class="col-xs-12 col-sm-6 col-md-4">
        @include('frontend.components.sites.' . config('app.directory_name') . '.user-summary', ['user' => $user])
    </div>
@endforeach