<footer class="Footer">
    <div class="container">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer_section">
                <h4 class="Footer__section-title">Wetenswardig</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('articles.overview') }}">Artikelen</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">Klantenservice</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="">Veelgestelde vragen</a>
                    </li>
                </ul>
            </div>
            </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">Over ons</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="{{ route('contact.get') }}">Bedrijfsgegevens</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="Footer__section">
                <h4 class="Footer__section-title">Juridish</h4>
                <ul class="Footer__section-list">
                    <li class="Footer__section-listItem">
                        <a href="">Algemene Voorwarden</a>
                    </li>
                    <li class="Footer__section-listItem">
                        <a href="">Privacybeleid</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-12 text-center Footer__logo-container">
            <div class="Footer__logo">
                <img src="{!! asset('img/site_logos/Altijdsex_LogoSmall_Pos@1x.jpg') !!}" alt="{{ config('app.name') }}">
            </div>
        </div>
    </div>
</footer>