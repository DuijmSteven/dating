<?php

namespace App\Managers;

use App\UserImage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StorageManager
{
    /**
     * @param UploadedFile $uploadedFile
     * @param string $path
     * @param string $location
     * @return mixed
     * @throws \Exception
     */
    public function saveFile(UploadedFile $uploadedFile, string $path, $location = 'cloud')
    {
        //Check if uploaded file is valid and upload it to cloud or save it locally
        if ($uploadedFile->isValid()) {
            try {
                $fileNameRoot = md5(microtime()
                    . $uploadedFile->getClientOriginalName()
                    . $uploadedFile->getClientSize());

                $fileNameMain = $fileNameRoot
                    . '.' . $uploadedFile->extension();

                $fileNameThumb = $fileNameRoot
                    .'_thumb'
                    . '.' . $uploadedFile->extension();

                $resource = $this->imageResize($uploadedFile, 180);

                $uploadThumb = Storage::disk($location)->put($path . $fileNameThumb, $resource);

                $uploadedFile->storeAs($path, $fileNameMain, $location);

                return $fileNameMain;
            } catch (\Exception $exception) {
                throw $exception;
            }
        } else {
            throw (new \Exception);
        }
    }

    public function getFile(string $fileName, string $path, $location = 'cloud')
    {
        $disk = Storage::disk($location);

        //Check if file exists and return url
        if ($disk->has($path . $fileName)) {
            return $disk->url($path . $fileName);
        } else {
            return false;
        }
    }

    public function fileExists(string $fileName, string $path, $location = 'cloud')
    {
        $disk = Storage::disk($location);

        //Check if file exists and return url
        if ($disk->exists($path . $fileName)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $userId
     * @param string $location
     * @return bool
     * @throws \Exception
     */
    public function saveUserPhoto(UploadedFile $uploadedFile, int $userId, $location = 'cloud')
    {
        return $this->saveFile(
            $uploadedFile,
            \StorageHelper::userImagesPath($userId),
            $location
        );
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param int $articleId
     * @param string $location
     * @return mixed
     * @throws \Exception
     */
    public function saveArticleImage(UploadedFile $uploadedFile, int $articleId, $location = 'cloud')
    {
        return $this->saveFile(
            $uploadedFile,
            \StorageHelper::articleImagesPath($articleId),
            $location
        );
    }

    /**
     * @param int $articleId
     * @param string $filename
     * @return bool
     */
    public function deleteArticleImage(int $articleId, string $filename)
    {
        if (Storage::disk('cloud')->exists(\StorageHelper::articleImagesPath($articleId) . $filename)) {
            Storage::disk('cloud')->delete(\StorageHelper::articleImagesPath($articleId) . $filename);

            $thumbFilenameParts = explode('.', $filename);
            $thumbFilename = $thumbFilenameParts[0] . '_thumb' . '.' . $thumbFilenameParts[1];

            return Storage::disk('cloud')->delete(\StorageHelper::articleImagesPath($articleId) . $thumbFilename);
        }

        return false;
    }

    public function saveConversationImage(UploadedFile $uploadedFile, int $conversationId, $location = 'cloud')
    {
        return $this->saveFile(
            $uploadedFile,
            \StorageHelper::messageAttachmentsPath($conversationId),
            $location
        );
    }

    /**
     * @param int $userId
     * @param string $filename
     * @return bool
     */
    public function deleteUserImage(int $userId, string $filename)
    {
        if (Storage::disk('cloud')->exists(\StorageHelper::userImagesPath($userId) . $filename)) {
            Storage::disk('cloud')->delete(\StorageHelper::userImagesPath($userId) . $filename);

            $thumbFilenameParts = explode('.', $filename);
            $thumbFilename = $thumbFilenameParts[0] . '_thumb' . '.' . $thumbFilenameParts[1];

            return Storage::disk('cloud')->delete(\StorageHelper::userImagesPath($userId) . $thumbFilename);
        }

        return false;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param int $height
     * @param int|null $width
     * @return mixed
     */
    private function imageResize(UploadedFile $uploadedFile, int $height, int $width = null)
    {
        /** @var \Intervention\Image\Image $img */
        $img = Image::make($uploadedFile);

        if (is_null($width)) {
            $img->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize($width, $height);
        }
        return $resource = $img->stream()->detach();
    }
}
