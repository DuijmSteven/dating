<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /** @var string */
    public static $userImagesPath = 'users/images/';

    /** @var string */
    public static $conversationImagesPath = 'conversations/images/';

    /**
     * @param int $userId
     * @param string $filename
     * @return mixed
     */
    public static function userImageUrl(int $userId, string $filename)
    {
        $filePath = self::$userImagesPath . $userId . '/' . $filename;
        return self::fileUrl($filePath);
    }

    /**
     * @param int $conversationId
     * @param string $filename
     * @return mixed
     */
    public static function conversationImageUrl(int $conversationId, string $filename)
    {
        $filePath = self::$conversationImagesPath . $conversationId . '/' . $filename;
        return self::fileUrl($filePath);
    }

    /**
     * @param string $filePath
     * @param string $location
     * @return mixed
     */
    public static function fileUrl(string $filePath, $location = 'cloud')
    {
        $disk = Storage::disk($location);
        return $disk->url($filePath);
    }
}
