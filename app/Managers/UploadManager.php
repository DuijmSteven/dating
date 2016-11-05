<?php

namespace App\Managers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager
{
    /**
     * @param UploadedFile $uploadedFile
     * @param $filename
     * @param string $path
     * @param string $location
     * @return bool
     */
    public function saveFile(UploadedFile $uploadedFile, $path = '', $location = 'cloud')
    {
        //Check if uploaded file is valid and upload it to cloud or save it locally
        if ($uploadedFile->isValid()) {
            try {
                $filepath =  $uploadedFile->store($path, $location, [
                    'visibility' => 'public',
                    'ContentType' => $_FILES['image'][]
                ]);
            } catch (\Exception $exception) {
                throw $exception;
            }

            return $filepath;
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

    public function saveUserPhoto(UploadedFile $uploadedFile, $userId, $location = 'cloud')
    {
        $filename = $this->saveFile($uploadedFile, 'users/photos/' . $userId, $location);

        $visibility = Storage::getVisibility($filename);
        return $filename;
    }
}
