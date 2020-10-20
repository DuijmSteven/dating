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
        .credits-page-content .paymentMethodsList .list-group-item:first-child,.credits-page-content .paymentMethodsList .list-group-item:last-child{border-radius:5px!important}.credits-page-content .step{margin-right:15px;color:#ce5338;font-weight:500;font-size:3.5rem}@media screen and (max-width:767px){.credits-page-content .step{margin-right:10px;font-size:2.3rem}}@media screen and (max-width:767px){.credits-page-content .stepTitle{font-size:1.9rem}}.credits-page-content p{color:#3c4857}.credits-page-content .header{padding:15px 0}.credits-page-content .h1,.credits-page-content h1{font-size:3.8em;line-height:1.15em}.credits-page-content .h2,.credits-page-content h2{font-size:2.6em}.credits-page-content .h3,.credits-page-content h3{font-size:1.825em;line-height:1.4em;margin:10px 0}.credits-page-content .h4,.credits-page-content h4{font-size:1.3em;line-height:1.55em}.credits-page-content .h5,.credits-page-content h5{font-size:1.25em;line-height:1.55em}.credits-page-content .h6,.credits-page-content h6{font-size:1em;text-transform:uppercase;font-weight:700}.credits-page-content .small,.credits-page-content small{font-size:1.4rem;line-height:1.2;font-weight:400}.credits-page-content .section-pricing{z-index:3;position:relative}.credits-page-content .section-gray{background:#e5e5e5}.credits-page-content .block{display:inline-block;position:relative;width:100%;margin-bottom:20px;border-radius:5px;color:rgba(0,0,0,.87);background:#fff;-webkit-box-shadow:0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);box-shadow:0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);cursor:pointer}@media (max-width:767px){.credits-page-content .block{margin-bottom:30px}}.credits-page-content .block-caption{font-weight:700;font-family:Fira Sans,Times New Roman,serif;color:#3c4857}.credits-page-content .block-plain{background:transparent;-webkit-box-shadow:none;box-shadow:none}.credits-page-content .paymentMethodsLabel{display:block;width:100%;padding-right:200px;z-index:20}@media (max-width:767px){.credits-page-content .paymentMethodsLabel{padding-right:40%}}.credits-page-content .paymentMethodsList{position:relative}.credits-page-content .paymentMethodLogo{height:29px;position:absolute;right:15px;top:13px}@media screen and (max-width:767px){.credits-page-content .paymentMethodLogo{height:20px;top:16px}}.credits-page-content .paymentMethodLogo img{height:100%}.credits-page-content .block .category:not([class*=text-]){color:#3c4857}.credits-page-content .block-background{background-position:50%;background-size:cover;text-align:center}.credits-page-content .block-raised{-webkit-box-shadow:0 16px 38px -12px rgba(0,0,0,.56),0 4px 25px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(0,0,0,.2);box-shadow:0 16px 38px -12px rgba(0,0,0,.56),0 4px 25px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(0,0,0,.2)}.credits-page-content .block-background .table{position:relative;z-index:2;min-height:280px;padding-top:40px;padding-bottom:40px;max-width:440px;margin:0 auto}.credits-page-content .block-background .block-caption{color:#fff;margin-top:10px}.credits-page-content .block-background:after{position:absolute;z-index:1;width:100%;height:100%;display:block;left:0;top:0;content:"";background-color:rgba(0,0,0,.56);border-radius:5px}.credits-page-content [class*=pricing-]{padding:0}@media (max-width:991px){.credits-page-content [class*=pricing-]{padding:0}}.credits-page-content .btn[type=submit]{font-size:16px}.credits-page-content .normalPrice{text-decoration:line-through}.credits-page-content .discountPrice{font-weight:700;color:#30d054}.credits-page-content .block-pricing{text-align:center}.credits-page-content .block-pricing .table{position:relative}.credits-page-content .block-pricing .table:not(.table-rose):hover{background:#f6e0dc}.credits-page-content .block-pricing .usp-label{position:absolute;margin:0 auto;top:-16px;right:0;height:30px;font-size:1.8rem;padding-left:15px;padding-right:15px;color:#fff;font-weight:500;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;border-radius:35px 3px 0}.credits-page-content .block-pricing .usp-label.most-popular{background-color:#41808e}.credits-page-content .block-pricing .usp-label.best-value{background-color:#67a243}.credits-page-content .block-pricing .table{padding:15px!important;margin-bottom:0}.credits-page-content .block-pricing .icon{padding:10px 0 0;color:#3c4857}.credits-page-content .block-pricing .icon i{font-size:55px;border:1px solid #ececec;border-radius:50%;width:130px;line-height:130px;height:130px}.credits-page-content .block-pricing h1 small{font-size:18px}.credits-page-content .block-pricing h1 small:first-child{position:relative;top:-17px;font-size:26px}.credits-page-content .block-pricing ul{list-style:none;padding:0;max-width:240px;margin:0px auto 10px}.credits-page-content .block-pricing ul li{color:#3c4857;text-align:center;padding:12px 0;border-bottom:1px solid hsla(0,0%,60%,.3)}.credits-page-content .block-pricing ul li:last-child{border:0}.credits-page-content .block-pricing ul li b{color:#3c4857}.credits-page-content .block-pricing ul li i{top:6px;position:relative}.credits-page-content .block-pricing.block-background ul li,.credits-page-content .block-pricing [class*=table-] ul li{color:#fff;border-color:hsla(0,0%,100%,.3)}.credits-page-content .block-pricing.block-background [class*=text-],.credits-page-content .block-pricing.block-background ul li b,.credits-page-content .block-pricing [class*=table-] [class*=text-],.credits-page-content .block-pricing [class*=table-] ul li b{color:#fff}.credits-page-content .block-pricing.block-background:after{background-color:rgba(0,0,0,.7)}.credits-page-content .block-background:not(.block-pricing) .btn{margin-bottom:0}.credits-page-content .block [class*=table-] .block-caption,.credits-page-content .block [class*=table-] .block-caption a,.credits-page-content .block [class*=table-] .icon i{color:#fff}.credits-page-content .block-pricing .block-caption{margin-top:30px}.credits-page-content .block [class*=table-] h1 small,.credits-page-content .block [class*=table-] h2 small,.credits-page-content .block [class*=table-] h3 small{color:hsla(0,0%,100%,.8)}.credits-page-content .block .table-primary{background:linear-gradient(60deg,#ab47bc,#7b1fa2);border-radius:5px;-webkit-box-shadow:0 16px 26px -10px rgba(156,39,176,.56),0 4px 25px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(156,39,176,.2);box-shadow:0 16px 26px -10px rgba(156,39,176,.56),0 4px 25px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(156,39,176,.2)}.credits-page-content .block .table-info{background:linear-gradient(60deg,#26c6da,#0097a7);border-radius:5px;-webkit-box-shadow:0 2px 2px 0 rgba(0,188,212,.14),0 3px 1px -2px rgba(0,188,212,.2),0 1px 5px 0 rgba(0,188,212,.12);box-shadow:0 2px 2px 0 rgba(0,188,212,.14),0 3px 1px -2px rgba(0,188,212,.2),0 1px 5px 0 rgba(0,188,212,.12)}.credits-page-content .block .table-success{background:linear-gradient(60deg,#66bb6a,#388e3c);border-radius:5px;-webkit-box-shadow:0 14px 26px -12px rgba(76,175,80,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(76,175,80,.2);box-shadow:0 14px 26px -12px rgba(76,175,80,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(76,175,80,.2)}.credits-page-content .block .table-warning{background:linear-gradient(60deg,#ffa726,#f57c00);border-radius:5px}.credits-page-content .block .table-danger{background:linear-gradient(60deg,#ef5350,#d32f2f);border-radius:5px;-webkit-box-shadow:0 2px 2px 0 rgba(221,75,57,.14),0 3px 1px -2px rgba(221,75,57,.2),0 1px 5px 0 rgba(221,75,57,.12);box-shadow:0 2px 2px 0 rgba(221,75,57,.14),0 3px 1px -2px rgba(221,75,57,.2),0 1px 5px 0 rgba(221,75,57,.12)}.credits-page-content .block .table-rose{background:linear-gradient(60deg,#ce5338,#ce5338);border-radius:5px;-webkit-box-shadow:0 14px 26px -12px rgba(233,30,99,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(233,30,99,.2);box-shadow:0 14px 26px -12px rgba(233,30,99,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(233,30,99,.2)}.credits-page-content .block [class*=table-] .block-description,.credits-page-content .block [class*=table-] .category{color:hsla(0,0%,100%,.8)}.credits-page-content .btn,.credits-page-content .navbar>li>a.btn{border:none;border-radius:5px;position:relative;padding:12px 30px;margin:10px 1px;font-size:12px;font-weight:400;text-transform:uppercase;letter-spacing:0;will-change:box-shadow,transform;-webkit-transition:background-color .2s cubic-bezier(.4,0,.2,1),-webkit-box-shadow .2s cubic-bezier(.4,0,1,1);transition:background-color .2s cubic-bezier(.4,0,.2,1),-webkit-box-shadow .2s cubic-bezier(.4,0,1,1);transition:box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1);transition:box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),-webkit-box-shadow .2s cubic-bezier(.4,0,1,1)}.credits-page-content .btn.btn-round{border-radius:5px}.credits-page-content .nav-tabs{margin-bottom:30px}.credits-page-content .nav-pills:not(.nav-pills-icons)>li>a{border-radius:30px;font-weight:400}.credits-page-content .nav-tabs .nav-item.show .nav-link,.credits-page-content .nav-tabs .nav-link.active{background-color:#9c27b0;color:#fff;-webkit-box-shadow:0 16px 26px -10px rgba(156,39,176,.56),0 4px 25px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(156,39,176,.2);box-shadow:0 16px 26px -10px rgba(156,39,176,.56),0 4px 25px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(156,39,176,.2);border-color:transparent;border-radius:30px}.credits-page-content .btn.btn-rose{color:#fff;background-color:#a9412a;border-color:#9d3c27}.credits-page-content .btn.btn-primary{color:#fff;background-color:#a9412a}.credits-page-content .btn.btn-primary:active,.credits-page-content .btn.btn-primary:focus,.credits-page-content .btn.btn-primary:hover{background-color:#ba472e}.credits-page-content .btn.btn-danger{color:#fff;background-color:#9e4737;border-color:#9e4737;-webkit-box-shadow:0 2px 2px 0 rgba(244,67,54,.14),0 3px 1px -2px rgba(244,67,54,.2),0 1px 5px 0 rgba(244,67,54,.12);box-shadow:0 2px 2px 0 rgba(244,67,54,.14),0 3px 1px -2px rgba(244,67,54,.2),0 1px 5px 0 rgba(244,67,54,.12)}.credits-page-content .btn.btn-danger:active,.credits-page-content .btn.btn-danger:focus,.credits-page-content .btn.btn-danger:hover{-webkit-box-shadow:0 14px 26px -12px rgba(244,67,54,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(244,67,54,.2);box-shadow:0 14px 26px -12px rgba(244,67,54,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(244,67,54,.2)}.credits-page-content .btn.btn-success{color:#fff;background-color:#4caf50;border-color:#4caf50;-webkit-box-shadow:0 2px 2px 0 rgba(76,175,80,.14),0 3px 1px -2px rgba(76,175,80,.2),0 1px 5px 0 rgba(76,175,80,.12);box-shadow:0 2px 2px 0 rgba(76,175,80,.14),0 3px 1px -2px rgba(76,175,80,.2),0 1px 5px 0 rgba(76,175,80,.12)}.credits-page-content .btn.btn-success:active,.credits-page-content .btn.btn-success:focus,.credits-page-content .btn.btn-success:hover{-webkit-box-shadow:0 14px 26px -12px rgba(76,175,80,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(76,175,80,.2);box-shadow:0 14px 26px -12px rgba(76,175,80,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(76,175,80,.2)}.credits-page-content .btn-info{color:#fff;background-color:#5bc0de;border-color:#5bc0de;-webkit-box-shadow:0 2px 2px 0 rgba(0,188,212,.14),0 3px 1px -2px rgba(0,188,212,.2),0 1px 5px 0 rgba(0,188,212,.12);box-shadow:0 2px 2px 0 rgba(0,188,212,.14),0 3px 1px -2px rgba(0,188,212,.2),0 1px 5px 0 rgba(0,188,212,.12)}.credits-page-content .btn.btn-info:active,.credits-page-content .btn.btn-info:focus,.credits-page-content .btn.btn-info:hover{-webkit-box-shadow:0 14px 26px -12px rgba(0,188,212,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(0,188,212,.2);box-shadow:0 14px 26px -12px rgba(0,188,212,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(0,188,212,.2)}.credits-page-content .btn-warning{color:#fff;background-color:#ff9800;border-color:#ff9800;-webkit-box-shadow:0 2px 2px 0 rgba(255,152,0,.14),0 3px 1px -2px rgba(255,152,0,.2),0 1px 5px 0 rgba(255,152,0,.12);box-shadow:0 2px 2px 0 rgba(255,152,0,.14),0 3px 1px -2px rgba(255,152,0,.2),0 1px 5px 0 rgba(255,152,0,.12)}.credits-page-content .btn.btn-warning:active,.credits-page-content .btn.btn-warning:focus,.credits-page-content .btn.btn-warning:hover{-webkit-box-shadow:0 14px 26px -12px rgba(255,152,0,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(255,152,0,.2);box-shadow:0 14px 26px -12px rgba(255,152,0,.42),0 4px 23px 0 rgba(0,0,0,.12),0 8px 10px -5px rgba(255,152,0,.2)}.credits-page-content .btn.btn-white{color:#3c4857;background-color:#fff}.credits-page-content .btn.btn-white:active,.credits-page-content .btn.btn-white:focus,.credits-page-content .btn.btn-white:hover{-webkit-box-shadow:0 4px 4px 0 hsla(0,0%,60%,.24),0 3px 1px -2px hsla(0,0%,60%,.3),0 1px 5px 0 hsla(0,0%,60%,.32);box-shadow:0 4px 4px 0 hsla(0,0%,60%,.24),0 3px 1px -2px hsla(0,0%,60%,.3),0 1px 5px 0 hsla(0,0%,60%,.32)}.credits-page-content .fa{font-size:12px}.credits-page-content .tab-space{padding:20px 0 50px}.credits-page-content .d-flex{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:justify!important;-ms-flex-pack:justify!important;justify-content:space-between!important;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.credits-page-content .recent-container{margin-top:20px;margin-bottom:30px}.credits-page-content .recent-container h5.small{font-size:1.8rem;font-weight:700;color:#ce5338;letter-spacing:1px;margin-top:1.375rem}.credits-page-content .recent-container img{max-width:170px}.credits-page-content li.list-group-item label{cursor:pointer}.credits-page-content .currentMembers .profileImage{-o-object-fit:cover;object-fit:cover;width:150px;height:150px}.credits-page-content .block-pricing h1{font-size:3.1rem}.credits-page-content .block-caption{color:#953925}.credits-page-content .paymentMethodsListItem.selected{background:linear-gradient(60deg,#ce5338,#ce5338);color:#fff}@media screen and (max-width:767px){.credits-page-content .block-pricing .block-caption{margin-top:10px;font-size:22px}.credits-page-content .block-pricing h1 small:first-child{position:relative;top:0;font-size:20px}.credits-page-content .block-pricing h6{margin-top:4px;margin-bottom:12px}.credits-page-content .block-pricing ul li{padding:0;font-size:16px}.credits-page-content .block-pricing .usp-label{top:-22px}}.credits-page-content .paymentButtonCheck{color:#00a65a;font-size:17px;font-weight:500;background-color:#fff;padding:6px;border-radius:50%;margin-left:5px}
        .credits-page-content {
            margin-top: 20px;
        }

        .credits-page-content .block-pricing .block-caption {
            margin-top: 10px !important;
        }

        .credits-page-content .btn {
            width: 100%;
            padding: 10px 5px !important;
            font-size: 10px !important;
        }

        .credits-page-content .block-pricing .block-caption {
            font-size: 24px;
        }

        .block-pricing .table:not(.table-rose):hover {
            background: initial !important;
        }

        .packContainer {
            width: 100%;
        }

        .packContainer .category {
            margin: 5px 0 15px;
        }

        .packContainer .block-caption {
            margin-bottom: 0;
        }


        @media screen and (min-width: 992px) {
            .packContainer {
                width: 22%;
                display: inline-block;
                margin-right: 15px;
            }
        }

        .allPacksContainer {
            text-align: center;
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
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #2c384e;">
<center style="width: 100%; background-color: #2c384e;">
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
                <img src="{!! asset('img/site_logos/' . config('app.directory_name') . '/Altijdsex_LogoSmall_Pos@1x_email.png') !!}" width="200" height="50" alt="Altijdsex.nl logo" border="0" style="height: auto; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555;">
            </td>
        </tr>
        <!-- Email Header : END -->

        <tr>
            <td>
                @yield('unsubscribe')
            </td>
        </tr>

        <!-- 1 Column Text + Button : BEGIN -->
        <tr>
            <td style="background-color: #ffffff; border-radius: 4px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td id="content-container-cell" style="text-align: justify; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                            @yield('content')
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <!-- 1 Column Text + Button : END -->

    </table>
    <!-- Email Body : END -->

    <!-- Email Footer : BEGIN -->
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: auto;" class="email-container">
        <tr>
            <td style="font-family: sans-serif; font-size: 16px; line-height: 15px; text-align: center; color: #ddd;">
                <br><br>
                Contact: <span class="unstyle-auto-detected-links">{{ config('company.info_email') }}<br></span>
                <br><br>
                <span style="font-size: 12px; color: #aaa">
                    Je kunt je voorkeuren voor e-mailmeldingen wijzigen door in te loggen op je account en op 'Bewerk Profiel' te klikken.
                </span>
{{--                <unsubscribe style="color: #888888; text-decoration: underline;">unsubscribe</unsubscribe>--}}
            </td>
        </tr>
    </table>
    <!-- Email Footer : END -->

    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
    <![endif]-->
</center>
</body>
</html>
