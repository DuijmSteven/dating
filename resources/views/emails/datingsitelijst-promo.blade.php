<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no"> <!-- Tell iOS not to automatically link certain text strings. -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
    <style>
    * {
        font-family: sans-serif !important;
    }

    </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        /* What it does: Fixes webkit padding issue. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }
        /* What it does: A work-around for email clients meddling in triggered links. */
        a[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links a,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        p {
            font-size: 17px;
        }
        /* What it does: Prevents Gmail from changing the text color in conversation threads. */
        .im {
            color: inherit !important;
        }
        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }
        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u ~ div .email-container {
                min-width: 320px !important;
            }
        }
        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u ~ div .email-container {
                min-width: 375px !important;
            }
        }
        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            u ~ div .email-container {
                min-width: 414px !important;
            }
        }
    </style>

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
    <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>
        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td-primary:hover,
        .button-a-primary:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        #content-container-cell {
            padding: 30px 30px;
        }

        @media screen and (min-width: 801px) {
            .email-container {
                width: 800px;
                max-width: 100% !important;
                margin: auto !important;
            }
        }

        @media screen and (max-width: 800px) {
            .email-container {
                width: 90% !important;
                margin: auto !important;
            }
        }

        /* Media Queries */
        @media screen and (max-width: 600px) {

            /* What it does: Adjust typography on small screens to improve readability */
            .email-container p {
                font-size: 17px !important;
            }
        }

        .UserSummary__profileImage {
            width: 40%;
            padding: 40px 0 10px;
        }

        .UserSummary__userInfo__primary {
            color: #555;
            font-weight: 500;
            font-size: 19px;
        }

        .UserSummary__userInfo__additional {
            font-size: 16px;
            color: #ce5338;
            font-weight: 400;
            margin-top: 5px;
        }

        @media screen and (max-width: 767px) {
            .UserSummary__profileImage {
                width: 80%;
                padding: 30px 0 10px;
            }
        }

        .LowProfileCompletion {
            font-weight: 400;
        }

        .LowProfileCompletion .Tile__body {
            position: relative;
        }

        .LowProfileCompletion .itemNotDone {
            color: red;
            position: relative;
            top: 5px;
        }

        .LowProfileCompletion .itemMedium {
            color: orange;
            position: relative;
        }

        .LowProfileCompletion .itemDone {
            color: green;
            position: relative;
        }
    </style>
    <!-- Progressive Enhancements : END -->

</head>
<!--
	The email background color (#222222) is defined in three places:
	1. body tag: for most email clients
	2. center tag: for Gmail and Inbox mobile apps and web versions of Gmail, GSuite, Inbox, Yahoo, AOL, Libero, Comcast, freenet, Mail.ru, Orange.fr
	3. mso conditional: For Windows 10 Mail
-->
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: {{ $mainColor }};">
<center style="width: 100%; background-color: {{ $mainColor }};">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #2c384e;">
    <tr>
        <td>
    <![endif]-->

    <!-- Email Body : BEGIN -->
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: auto;" class="email-container">
        <!-- Email Header : BEGIN -->
        <tr>
            <td style="padding: 20px 0; text-align: center">
                <img src="{!! asset('img/datingsitelijst-logo.png') !!}" width="200" height="50" alt="Datingsite Lijst" border="0" style="height: auto; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555;">
            </td>
        </tr>
        <!-- Email Header : END -->

        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td style="background-color: #ffffff; border-radius: 4px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td id="content-container-cell" style="text-align: justify; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">



                            <h1 style="margin: 0 0 30px; font-size: 25px; line-height: 30px; color: #333333; font-weight: bold; text-align: center">
                                Probeer deze datingsites eens!
                            </h1>

                            <p style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Beste {{ $user->username }},</p>

                            <p style="margin-bottom: 0">
                                Bedankt voor het gebruiken van onze diensten. Dagelijks brengen we de beste datingsites in kaart, we stellen jou dan ook graag op de hoogte van de beste datingsites van dit moment. Inschrijven op door ons geselecteerde datingsites is altijd gratis, zonder abonnement of betaling toegankelijk. Niets te verliezen dus!
                            </p>

                            <br>
                            <br>
                            <div style="background: #333; padding-left: 12px; padding-top: 5px; text-align: center">
                                <a target="_blank" href="https://datevrij.nl?affiliate=datingsitelijstpromo">
                                    <img height="50px" src="{{ asset('img/site_logos/datevrij-nl/main_logo.png') }}" alt="Datevrij.nl">
                                </a>
                            </div>

                            <p style="margin-bottom: 0">
                                Datevrij is de beste erotische datingsite van dit moment. Met 55.000+ actieve leden zit er altijd wel iemand voor jou bij. Of je nu op zoek bent naar een one-night-stand aan de andere kant van het land of meerdere contacten bij jou in de buurt.
                            </p>

                            <div style="text-align: center">
                                <div style="display: inline-block; padding: 7px 20px; background-color: {{ $datevrijSecondaryColor }}; color: #fff; border: 1px solid {{ $datevrijSecondaryColor }}; border-radius: 4px; margin: 20px 0; cursor: pointer">
                                    <a target="_blank" style="color: #fff" href="https://datevrij.nl?affiliate=datingsitelijstpromo">Naar Datevrij.nl!</a>
                                </div>
                            </div>

                            <br>
                            <div style="background: #cb0e46; padding-left: 15px; padding-top: 5px; text-align: center">
                                <a target="_blank" href="https://sweetalk.nl?affiliate=datingsitelijstpromo">
                                    <img height="50px" src="{{ asset('img/site_logos/sweetalk-nl/main_logo.png') }}" alt="Sweetalk.nl">
                                </a>
                            </div>

                            <p style="margin-bottom: 0">
                                Deze datingsite is meer gericht op vriendschap, liefde en relaties. Wanneer je het zat bent dat het op de meeste datingsites steeds maar over seks gaat, Sweetalk houdt het netjes. Bovendien voorziet deze datingsite je ruimschoots van handige functies, waardoor je makkelijk iemand kan vinden om mee te chatten, en wie weet wel meer?
                            </p>

                            <div style="text-align: center">
                                <div style="display: inline-block; padding: 7px 20px; background-color: {{ $sweetalkSecondaryColor }}; color: #fff; border: 1px solid {{ $sweetalkSecondaryColor }}; border-radius: 4px; margin: 20px 0; cursor: pointer">
                                    <a target="_blank" style="color: #fff" href="https://sweetalk.nl?affiliate=datingsitelijstpromo">Naar Sweetalk.nl!</a>
                                </div>
                            </div>

                            <br>
                            <div style="background: #444; padding-left: 5px; padding-top: 5px; padding-bottom: 2px; text-align: center">
                                <a target="_blank" href="https://liefdesdate.nl?affiliate=datingsitelijstpromo">
                                    <img height="50px" src="{{ asset('img/site_logos/liefdesdate-nl/main_logo.png') }}" alt="Liefdesdate.nl">
                                </a>
                            </div>

                            <p style="margin-bottom: 0">
                                Op Liefdesdate kom je voornamelijk singles tegen op zoek naar een relatie. Met op dit moment maar liefst 42.500 ingeschreven singles behoort deze datingsite tot de grotere datingsites van dit moment. Een leuk feit: 1 op de 3 (ruim 33%) vindt binnen korte tijd een date/relatie. Mannen hebben hier meer kans van slagen dan vrouwen, 58% van de gebruikers is namelijk vrouw, 42% is man.
                            </p>

                            <div style="text-align: center">
                                <div style="display: inline-block; padding: 7px 20px; background-color: {{ $liefdesdateSecondaryColor }}; color: #fff; border: 1px solid {{ $liefdesdateSecondaryColor }}; border-radius: 4px; margin: 20px 0; cursor: pointer">
                                    <a target="_blank" style="color: #fff" href="https://liefdesdate.nl?affiliate=datingsitelijstpromo">Naar Liefdesdate.nl!</a>
                                </div>
                            </div>


                            <p style="font-family: sans-serif; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                Met vriendelijke groet,<br>
                                Team <a target="_blank" href="https://datingsitelijst.nl?affiliate=datingsitelijstpromo">Datingsitelijst.nl</a>
                            </p>



                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <!-- 1 Column Text + Button : END -->

    </table>
    <!-- Email Body : END -->
    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
    <![endif]-->
</center>
</body>
</html>
