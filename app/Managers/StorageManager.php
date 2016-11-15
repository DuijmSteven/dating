<?php

namespace App\Managers;

use App\UserImage;
use Illuminate\Support\Facades\Storage;
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
    public function saveFile(UploadedFile $uploadedFile, $path = '', $location = 'cloud')
    {
        //Check if uploaded file is valid and upload it to cloud or save it locally
        if ($uploadedFile->isValid()) {
            try {
                $filename = md5(microtime()
                    . $uploadedFile->getClientOriginalName()
                    . $uploadedFile->getClientSize());

                $uploadedFile->storeAs($path, $filename, $location);
            } catch (\Exception $exception) {
                throw $exception;
            }

            return $filename;
        } else {
            throw (new \Exception);
        }
    }

    public function getFile($filename, $path = '', $location = 'cloud')
    {
        $disk = Storage::disk($location);

        //Check if file exists and return url
        if ($disk->has($path . $filename)) {
            return $disk->url($path . $filename);
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
    public function saveUserPhoto(UploadedFile $uploadedFile, $userId, $location = 'cloud')
    {
        return $this->saveFile($uploadedFile, \StorageHelper::userImagesPath . $userId, $location);
    }

    /**
     * @param $imageId
     * @return mixed
     */
    public function deleteImage($imageId)
    {
        /** @var UserImage $image */
        $image = UserImage::findOrFail($imageId);

        if(Storage::disk('cloud')->exists($image->filename)) {
            $deleted = Storage::disk('cloud')->delete($image->filename);
            return $deleted;
        }
        return false;
    }
}
