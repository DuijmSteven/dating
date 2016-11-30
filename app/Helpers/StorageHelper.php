<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static $userImagesPath = 'users/images/';

    public static function userImageUrl(int $userId, string $filename)
    {
        $filePath = self::$userImagesPath . $userId . '/' . $filename;
        return self::fileUrl($filePath);
    }

    public static function fileUrl(string $filePath, $location = 'cloud')
    {
        $disk = Storage::disk($location);
        return $disk->url($filePath);
    }
}
