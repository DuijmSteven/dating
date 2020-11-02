<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\Storage;

/**
 * Class StorageHelper
 * @package App\Helpers
 */
class StorageHelper
{
    /** @var string */
    public static $usersDir = 'users/';

    /** @var string */
    public static $userImagesDir = 'images/';

    /** @var string */
    public static $articlesDir = 'articles/';

    /** @var string */
    public static $articleImagesDir = 'images/';

    /** @var string */
    public static $conversationsDir = 'conversations/';

    /** @var string */
    public static $conversationAttachmentsDir = 'attachments/';

    public static $genderAvatarsDir = 'img/avatars/';

    /**
     * @param $user
     * @return mixed|string
     * @throws \Exception
     */
    public static function profileImageUrl(User $user, bool $thumb = false)
    {
        if (!$user->hasProfileImage()) {
            return self::getGenderImageUrl($user);
        }

        $filePath = self::userImagesPath($user->id) . $user->profileImage->filename;

        if ($thumb) {
            $explodedFilename = explode('.', $filePath);

            $thumbFilePath = $explodedFilename[0] . '_thumb' . '.' . $explodedFilename[1];
            return self::fileUrl($thumbFilePath);
        }
        return self::fileUrl($filePath);
    }


    /**
     * @param int $articleId
     * @param string|null $filename
     * @return mixed
     * @throws \Exception
     */
    public static function articleImageUrl(int $articleId, string $filename = null, $thumb = false)
    {

        $filePath = self::articleImagesPath($articleId) . $filename;

        if ($thumb) {
            $explodedFilename = explode('.', $filePath);

            $thumbFilePath = $explodedFilename[0] . '_thumb' . '.' . $explodedFilename[1];
            return self::fileUrl($thumbFilePath);
        }
        return self::fileUrl($filePath);
    }

    /**
     * @param int $userId
     * @param string $gender
     * @param string|null $filename
     * @return mixed|string
     */
    public static function profileImageUrlFromId(int $userId, $gender, string $filename = null)
    {
        if (!in_array($gender, [1, 2, '1', '2', 'male', 'female'])) {
            \Log::error(__FUNCTION__ . ' in ' . __CLASS__ . ' : Wrong gender parameter passed (' . $gender . ')');
        }

        if ($gender == '1' || $gender == '2') {
            $gender = \UserConstants::selectableField('gender')[(int) $gender];
        }

        if (is_null($filename)) {
            return asset(self::$genderAvatarsDir . 'sites/' . config('app.directory_name') . '/' . $gender . '.png');
        }
        return self::userImageUrl($userId, $filename);
    }

    /**
     * @param int $userId
     * @param string $filename
     * @return mixed|string
     */
    public static function userImageUrl(int $userId, string $filename = null, bool $thumb = false)
    {
        $filePath = self::userImagesPath($userId) . $filename;

        if ($thumb) {
            $explodedFilename = explode('.', $filePath);

            $thumbFilePath = $explodedFilename[0] . '_thumb' . '.' . $explodedFilename[1];
            return self::fileUrl($thumbFilePath);
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
     * @param int $articleId
     * @return string
     */
    public static function articleImagesPath(int $articleId)
    {
        return self::$articlesDir . $articleId . '/' . self::$articleImagesDir;
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

    /**
     * @param User $user
     * @return string
     * @throws \Exception
     */
    private static function getGenderImageUrl(User $user): string
    {
        return asset(self::$genderAvatarsDir . 'sites/' . config('app.directory_name') . '/' . \UserConstants::selectableField('gender')[$user->meta->gender] . '.jpg');
    }
}
