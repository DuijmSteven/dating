@if(
    isset($authenticatedUser) &&
    !($authenticatedUser->isOperator() || $authenticatedUser->isEditor())
)
    <div class="pull-right hidden-xs">
        Datevrij.nl
    </div>
    <strong><a href="{{ url('/') }}">Datevrij.nl</a></strong>
@endif