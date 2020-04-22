<div id="analytics--welcomeModal" class="modal welcomeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="position: relative; z-index: 10000">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center; background-color: #312c2c">
                <img style="width: 40%" src="{!! asset('img/site_logos/Altijdsex_LogoSmall_Pos@1x.png') !!}">
            </div>
            <div class="modal-body">
                <div class="show-mobile">
                    <h3>Welkom {{ $authenticatedUser->username }}!</h3>

                    <p style="text-align: justify">
                        Bedankt dat je voor Altijdsex.nl hebt gekozen! Je kunt nu direct je zoektocht starten, zodat je binnenkort kunt genieten van een spannende date.
                    </p>

                    <div class="well" style="text-align: justify; font-weight: 500;margin-bottom: 0">
                        <strong style="font-size: 2.7rem;color: #ef4e27;margin-right: 11px;line-height: 2.7rem;">TIP</strong> Wist je dat leden die een <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielafbeelding uploaden</a>
                        en hun <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielinformatie invullen</a>,
                        tot wel 70% meer reacties krijgen?
                    </div>
                </div>

                <div class="hide-mobile">
                    <h3>Welkom {{ $authenticatedUser->username }}!</h3>
                    <p style="text-align: justify">
                        Bedankt dat je voor Altijdsex.nl hebt gekozen. Je kunt de wesite nu gelijk gebruiken en op zoek gaan naar iemand die bij jou past.
                        Ons doel is om je te voorzien van de handvatten die je nodig hebt om snel en eenvoudig een spannende date te vinden en je de best
                        mogelijke ervaring te bieden.

                        We hopen dat je veel plezier beleeft aan het gebruik van onze moderne chat vorzien van <strong>live
                            updates en meldingen</strong>, zodat je binnenkort je volgende sexy date(s) kunt ontmoeten.
                        <br><br>
                        Onthoud dat mensen die een <strong>profielafbeelding</strong></a> uploaden en hun profielinformatie invullen, veel grotere kansen hebben om mensen succesvol te ontmoeten!
                    </p>
                    <div class="well" style="text-align: justify; font-weight: 500;margin-bottom: 0">
                        <strong style="font-size: 2.7rem;color: #ef4e27;margin-right: 11px;line-height: 2.7rem;">TIP</strong>Leden die een <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielfoto uploaden</a>
                        en hun <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielinformatie invullen</a>,
                        hebben 70% meer kans op contact en een successvolle sexdate!
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 0; padding-top: 0">
                <form method="post" action="{{ route('users.redirect-back') }}">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ $authenticatedUser->getId() }}">
                    <input type="hidden" name="milestone_id" value="1">

                    <button
                        type="submit"
                        class="Button-fw Button--primary Button whiteFont"
                    >
                        <span class="Button__content">{{ trans('buttons.close') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>