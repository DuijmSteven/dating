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
     * @param $user
     * @return mixed|string
     * @throws \Exception
     */
    public static function profileImageUrl(User $user, bool $thumb = false)
    {
        if (is_null($user)) {
            throw new \Exception();
        }

        if (is_null($user->profile_image)) {
            return 'http://placehold.it/100x150';
        }

        $filePath = self::userImagesPath($user->id) . $user->profile_image->filename;

        if (!Storage::disk('cloud')->exists($filePath)) {
            // TODO
            return 'http://placehold.it/100x150';
        }

        if ($thumb) {
            $explodedFilename = explode('.', $filePath);

            $thumbFilePath = $explodedFilename[0] . '_thumb' . '.' . $explodedFilename[1];
            return self::fileUrl($thumbFilePath);
        }
        return self::fileUrl($filePath);
    }

    public static function userImageUrl(int $userId, string $filename)
    {
        if (is_null($filename)) {
            return 'http://placehold.it/100x100';
        }
        $filePath = self::userImagesPath($userId) . $filename;

        if (!Storage::disk('cloud')->exists($filePath)) {
            // TODO
            return 'http://placehold.it/100x100';
        }

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
