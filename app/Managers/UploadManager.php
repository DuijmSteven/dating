<?php

namespace App\Managers;

use Storage;
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
    public function saveFile(UploadedFile $uploadedFile, $filename, $path = '', $location = 'cloud')
    {
        $disk = Storage::disk($location);

        //Check if uploaded file is valid and upload it to cloud or save it locally
        if ($uploadedFile->isValid()) {
            $disk->put($path.$filename, file_get_contents($uploadedFile));
            return true;
        } else {
            return false;
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
        $filename = md5($uploadedFile->getClientOriginalName() . microtime() . $userId) . '.' .
                    $uploadedFile->getClientOriginalExtension();

        $this->saveFile($uploadedFile, $filename, 'users/photos/' . $userId . '/', $location);

        return $filename;
    }
}
