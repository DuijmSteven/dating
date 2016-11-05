<?php

namespace App\Helpers;

use Storage;

class StorageHelper
{
    public static function fileUrl($filename = '', $location = 'cloud')
    {
        $disk = Storage::disk($location);
        return $disk->url($filename);
    }
}