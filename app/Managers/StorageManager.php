<?php

namespace App\Managers;

use App\UserImage;
use Illuminate\Support\Facades\Storage;
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
                $fileName = md5(microtime()
                    . $uploadedFile->getClientOriginalName()
                    . $uploadedFile->getClientSize())
                    . '.' . $uploadedFile->extension();

                $fileNameThumb = md5(microtime()
                        . $uploadedFile->getClientOriginalName()
                        . $uploadedFile->getClientSize())
                        .'_thumb'
                        . '.' . $uploadedFile->extension();

                $thumb = Image::make($uploadedFile)->resize(50, 100);

                $thumb->save(public_path($fileNameThumb));

                $thumbUpload = Storage::disk($location)->putFileAs($path, new File(public_path($fileNameThumb)), $fileNameThumb);

                unlink(public_path($fileNameThumb));

                $uploadedFile->storeAs($path, $fileName, $location);

                return $fileName;
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

    /**
     * @param UploadedFile $uploadedFile
     * @param $userId
     * @param string $location
     * @return bool
     */
    public function saveUserPhoto(UploadedFile $uploadedFile, int $userId, $location = 'cloud')
    {
        return $this->saveFile(
            $uploadedFile,
            \StorageHelper::userImagesPath($userId),
            $location
        );
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
    public function deleteImage(int $userId, string $filename)
    {
        if (Storage::disk('cloud')->exists(\StorageHelper::userImagesPath($userId) . $filename)) {
            $deleted = Storage::disk('cloud')->delete(\StorageHelper::userImagesPath($userId) . $filename);
            return $deleted;
        }
        return false;
    }
}
