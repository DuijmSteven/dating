<div class="Tile LowProfileCompletion">
    <div class="Tile__heading">
        <i class="material-icons warningSign">warning</i> Verbeter jouw profiel
    </div>
    <div class="Tile__body">
        <i>1) Profielfoto ingesteld</i>

        @if(!$authenticatedUser->profileImage)
            <i class="material-icons itemNotDone">close</i>
        @else
            <i class="material-icons itemDone">done</i>
        @endif

        <br>
        <i>2) Profielvelden ingevuld</i> {{ $user->profileRatioFilled*100 }}%

        @if($authenticatedUser->profileRatioFilled < 0.3)
            <i class="material-icons itemNotDone">close</i>
        @elseif($authenticatedUser->profileRatioFilled < 0.7)
            <i class="material-icons itemMedium">error_outline</i>
        @else
            <i class="material-icons itemDone">done</i>
        @endif
        <br>
        <br>

        Leden die een <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielafbeelding uploaden</a>
        en hun <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielinformatie invullen</a>,
        hebben <b>70% meer kans op contact</b> en een successvolle sexdate!

{{--        <div class="progress">--}}
{{--            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{ $user->profileRatioFilled*100 }}%;">--}}
{{--                <span class="sr-only">{{ $user->profileRatioFilled*100 }}% Complete</span>--}}
{{--                <span class="percentageText">{{ $user->profileRatioFilled*100 }}%</span>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <div class="Tile__footer">
        <form method="post" action="{{ route('users.current.accept-profile-completion-message') }}">
            <div class="text-right">
                @csrf

                @include('frontend.components.button', [
                  'buttonContext' => 'form',
                  'buttonType' => 'submit',
                  'buttonState' => 'info',
                  'buttonText' => @trans('low_profile_completion.close_message')
                ])
            </div>
        </form>
    </div>

</div>