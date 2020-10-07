<footer class="Footer">
    <div class="container">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer_section">
                <h4 class="Footer__section-title">{{ @trans('footer.noteworthy') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('articles.overview') }}">{{ @trans('footer.articles') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">{{ @trans('footer.client_service') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('faq.show') }}">{{ @trans('footer.faq') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">{{ @trans('footer.about_us') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('contact.get') }}">{{ @trans('footer.company_data') }}</a>
                    </li>
                    <li class="Footer__section-listItem">
                        <a href="{{ route('contact.get') }}">{{ @trans('footer.contact') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">{{ @trans('footer.legal') }}</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('tac.show') }}">{{ @trans('footer.tac') }}</a>
                    </li>
                    <li class="Footer__section-listItem">
                        <a href="{{ route('privacy.show') }}">{{ @trans('footer.privacy') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        @if(
            !isset($isAnonymousDomain) ||
            !$isAnonymousDomain
        )
            <div class="col-xs-12 text-center Footer__logo-container">
                <div class="Footer__logo">
                    <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/Altijdsex_LogoBig_Neg.svg') !!}" alt="{{ ucfirst(\config('app.name')) }}">
                </div>
                <div class="col-md-12 copyright">
                    <h5>{{ @trans('footer.copyright', ['currentYear' => $carbonNow->year]) }}</h5>
                </div>
            </div>
        @endif
    </div>
</footer>