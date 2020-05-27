@if(
    isset($authenticatedUser) &&
    !($authenticatedUser->isOperator() || $authenticatedUser->isEditor())
)
    <div class="pull-right hidden-xs">
        Altijdsex.nl
    </div>
    <strong><a href="{{ url('/') }}">Altijdsex.nl</a></strong>
@endif