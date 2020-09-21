<div id="analytics--welcomeModal" class="modal welcomeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="position: relative; z-index: 10000">
        <div class="modal-content">
{{--            <div class="modal-header" style="text-align: center; background-color: #312c2c">--}}
{{--                <img style="width: 40%" src="{!! asset('img/site_logos/Altijdsex_LogoSmall_Pos@1x.png') !!}">--}}
{{--            </div>--}}
            <div class="modal-body">
                <div class="show-mobile">
                    <h3 class="heading">
                        <span class="material-icons">
                            check_circle_outline
                        </span>
                        Bedankt voor je aanmelding {{ $authenticatedUser->username }}!
                    </h3>
                    <hr class="heading-undeline">

                    <p>
                        Er is een e-mail verstuurd naar <span class="user-email">{{ $authenticatedUser->email }}</span>. Als je geen email hebt ontvangen, check je spambox!
                    </p>

                    <div class="email-warning">
                        <div class="sub-heading">
                            <span class="material-icons">
                                warning
                            </span>
                            <span>Verkeerd e-mailadres ingevuld?</span>
                        </div>
                         <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">E-mailadres aanpassen!</a>
                    </div>

                    <div class="well" style="font-weight: 500;margin-bottom: 0">
                        <strong style="font-size: 2.7rem;color: #ef4e27;margin-right: 11px;line-height: 2.7rem;">TIP</strong> Wist je dat leden die een <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielafbeelding uploaden</a>
                        en hun <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielinformatie invullen</a>,
                        tot wel 70% meer reacties krijgen?
                    </div>
                </div>

                <div class="hide-mobile">
                    <h3 class="heading">
                        <span class="material-icons">
                            check_circle_outline
                        </span>
                        Bedankt voor je aanmelding {{ $authenticatedUser->username }}!
                    </h3>
                    <hr class="heading-undeline">

                    <p>
                        Er is een e-mail verstuurd naar <span class="user-email">{{ $authenticatedUser->email }}</span>. Als je geen email hebt ontvangen, check je spambox!
                    </p>

                    <div class="email-warning">
                        <div class="sub-heading">
                            <span class="material-icons">
                                warning
                            </span>
                            <span>Verkeerd e-mailadres ingevuld?</span>
                        </div>
                        <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">E-mailadres aanpassen!</a>
                    </div>

                    <div class="well" style="font-weight: 500;margin-bottom: 0">
                        <strong style="font-size: 2.7rem;color: #ef4e27;margin-right: 11px;line-height: 2.7rem;">TIP</strong> Wist je dat leden die een <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielafbeelding uploaden</a>
                        en hun <a href="{{ route('users.edit-profile.get', ['username' => $authenticatedUser->username]) }}">profielinformatie invullen</a>,
                        tot wel 70% meer reacties krijgen?
                    </div>

                    <br>

                    We hopen dat je veel plezier beleeft aan het gebruik van onze moderne chat voorzien van <strong>live
                        updates en meldingen</strong>, zodat je binnenkort je volgende sexy date(s) kunt ontmoeten.
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