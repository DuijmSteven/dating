@if(count($users) == 0)
    <div>
        <p style="margin-bottom: 50px">
            There are no users matching the search criteria. Please try another search.
        </p>
    </div>
@endif
@foreach($users as $user)
    <div class="col-sm-12 col-md-4">
        @include('frontend.components.user-summary', ['user' => $user])
    </div>
@endforeach