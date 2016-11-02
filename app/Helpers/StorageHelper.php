<?php

namespace App\Helpers;

use Storage;

class StorageHelper
{
    public static function fileUrl($filename = '', $path = '', $location = 'cloud')
    {
        $disk = Storage::disk($location);
        return $disk->url($path . '/' . $filename);
    }
}