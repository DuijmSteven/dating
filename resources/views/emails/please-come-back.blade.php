

    <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
        Tijdelijk {{ $user->getDiscountPercentage() }}% korting voor je, {{ ucfirst($user->getUsername()) }}!
    </h1>

    <p style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

    <p style="margin-bottom: 0">
        Enige tijd geleden was je voor het laatst actief op
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->getId(), 'routeName' => 'credits.show', null, null]); @endphp">{{ ucfirst(config('app.name')) }}</a>
        en een aantal gewillige leden hebben aangegeven je te missen! Zoek je nog anonieme, vrijblijvende sensuele contacten? Kom dan gerust terug naar
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->getId(), 'routeName' => 'credits.show', null, null]); @endphp">{{ ucfirst(config('app.name')) }}</a>
        en profiteer van <b>{{ $user->getDiscountPercentage() }}% korting</b>.
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: {{ $mainColor }}; color: #fff; border: 1px solid {{ $mainColor }}; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->getId(), 'routeName' => 'credits.show', null, null]); @endphp">Ga direct naar credits pagina!</a>
        </div>
    </div>

    <p style="margin-bottom: 0">
        Elke dag weer zijn er duizenden vrouwen die zich met dezelfde intenties inschrijven op
        <a href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->getId(), 'routeName' => 'credits.show', null, null]); @endphp">{{ ucfirst(config('app.name')) }}</a>.
        Daar zal vast en zeker ook wel een gelijkgestemde voor jou tussen zitten, toch? Speciaal om jouw kansen te vergroten bieden we je momenteel <b>{{ $user->getDiscountPercentage() }}% korting</b> op je volgende aankoop.
    </p>

    <div style="text-align: center">
        <div style="display: inline-block; padding: 7px 20px; background-color: {{ $secondaryColor }}; color: #fff; border: 1px solid {{ $secondaryColor }}; border-radius: 4px; margin: 20px 0; cursor: pointer">
            <a style="color: #fff" href="@php URL::forceRootUrl(\config('app.url')); echo URL::signedRoute('direct-login', ['user' => $user->getId(), 'routeName' => 'credits.show', null, null]); @endphp">Profiteer van deze korting!</a>
        </div>
    </div>

    <p>Geniet van deze aanbieding en een heel spannende tijd toegewenst!</p>

    <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
        Met vriendelijke groet,<br>
        Team Datingsitelijst.nl
    </p>

