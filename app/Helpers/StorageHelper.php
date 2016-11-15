<?php

namespace App\Helpers;

use Storage;

class StorageHelper
{
    public static $userImagesPath = 'users/images/';

    public static function userImageUrl($userId, $filename = '')
    {
        $filePath = self::$userImagesPath . $userId . '/' . $filename;
        return self::fileUrl($filePath);
    }

    private function fileUrl($filepath = '', $location = 'cloud')
    {
        $disk = Storage::disk($location);
        return $disk->url($filepath);
    }
}