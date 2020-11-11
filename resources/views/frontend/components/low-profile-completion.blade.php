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

        Leden die een <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielfoto uploaden</a>
        en hun <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielinformatie invullen</a>,
        hebben <b>70% meer kans op contact</b> en een successvolle sexdate!

    </div>
    <div class="Tile__footer">
        <form method="post" action="{{ route('users.current.accept-profile-completion-message') }}">
            <div class="text-right">
                @csrf

                @include('frontend.components.button', [
                  'buttonContext' => 'form',
                  'buttonType' => 'submit',
                  'buttonState' => 'primary',
                  'buttonText' => @trans(config('app.directory_name') . '/low_profile_completion.close_message')
                ])
            </div>
        </form>
    </div>

</div>