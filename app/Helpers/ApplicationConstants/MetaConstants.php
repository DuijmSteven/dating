<?php namespace App\Helpers\ApplicationConstants;

/**
 * Class MetaConstants
 * @package App\Helpers\ApplicationConstants
 */
class MetaConstants
{
    public static $siteName = 'Dating platform';

    /**
     * @return string
     */
    public static function getSiteName()
    {
        return self::$siteName;
    }
}
