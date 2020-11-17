<?php

namespace App\Helpers;

class SiteHelper
{
    const ALTIJDSEX_NL = 1;
    const LIEFDESDATE_NL = 2;
    const SWEETALK_NL = 3;

    public static function siteNamePerId()
    {
        return [
            self::ALTIJDSEX_NL => 'Altijdsex.nl',
            self::LIEFDESDATE_NL => 'Liefdesdate.nl',
            self::SWEETALK_NL => 'Sweetalk.nl'
        ];
    }
}
