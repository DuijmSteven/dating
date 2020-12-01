@include('frontend.components.sites.' . config('app.directory_name') . '.user-summary', [
    'user' => $user,
    'showOtherImages' => true,
    'noInfo' => true,
    'noButton' => true
])