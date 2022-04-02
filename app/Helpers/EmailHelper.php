<?php

namespace App\Helpers;

class EmailHelper
{
    public static function getSiteMainColor()
    {
        if ((int) config('app.site_id') === SiteHelper::DATEVRIJ_NL) {
            return '#312c2c';
        }

        if ((int) config('app.site_id') === SiteHelper::LIEFDESDATE_NL) {
            return '#393939';
        }

        if ((int) config('app.site_id') === SiteHelper::SWEETALK_NL) {
            return '#111';
        }
    }

    public static function getSiteSecondaryColor()
    {
        if ((int) config('app.site_id') === SiteHelper::DATEVRIJ_NL) {
            return '#ce5338';
        }

        if ((int) config('app.site_id') === SiteHelper::LIEFDESDATE_NL) {
            return '#ce5a5a';
        }

        if ((int) config('app.site_id') === SiteHelper::SWEETALK_NL) {
            return '#910432';
        }
    }

    public static function getSiteTertiaryColor()
    {
        if ((int) config('app.site_id') === SiteHelper::DATEVRIJ_NL) {
            return '#4f5d75';
        }

        if ((int) config('app.site_id') === SiteHelper::LIEFDESDATE_NL) {
            return '#81dada';
        }

        if ((int) config('app.site_id') === SiteHelper::SWEETALK_NL) {
            return '#EB2157';
        }
    }
}
