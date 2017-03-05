<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /** @var string */
    public static $usersDir = 'users/';

    /** @var string */
    public static $userImagesDir = 'images/';

    /** @var string */
    public static $conversationsDir = 'conversations/';

    /** @var string */
    public static $conversationAttachmentsDir = 'attachments/';

    /**
     * @param int $userId
     * @param string $filename
     * @return mixed
     */
    public static function userImageUrl(User $user)
    {
        if (!$user->hasProfileImage()) {
            // TODO
            return 'http://placehold.it/100x150';
        }

        if (is_null($user->profile_image->filename)) {
            throw new \Exception;
        }
        $filePath = self::userImagesPath($user->id) . $user->profile_image->filename;
        return self::fileUrl($filePath);
    }

    /**
     * @param int $userId
     * @return string
     */
    public static function userImagesPath(int $userId)
    {
        return self::$usersDir . $userId . '/' . self::$userImagesDir;
    }

    /**
     * @param int $conversationId
     * @param string $filename
     * @return mixed
     */
    public static function messageAttachmentUrl(int $conversationId, string $filename)
    {
        $filePath = self::$conversationsDir . $conversationId . '/' . self::$conversationAttachmentsDir . $filename;
        return self::fileUrl($filePath);
    }

    /**
     * @param int $conversationId
     * @return string
     */
    public static function messageAttachmentsPath(int $conversationId)
    {
        return self::$conversationsDir . $conversationId . '/' . self::$conversationAttachmentsDir;
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
