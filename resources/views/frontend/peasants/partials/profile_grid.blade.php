@if(count($users) == 0)
    There are no users matching the search criteria. Please try another search.
@endif
@foreach($users as $user)
    <div class="col-sm-12 col-md-4">
        @include('frontend.components.user-summary')
    </div>
@endforeach