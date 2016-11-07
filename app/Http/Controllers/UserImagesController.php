<?php

namespace App\Http\Controllers;


use App\Managers\StorageManager;
use App\UserImage;

class UserImagesController extends Controller
{
    /** @var StorageManager */
    private $storageManager;

    public function __construct(StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    public function destroy($imageId)
    {
        if ($this->storageManager->deleteImage($imageId)) {
            $image = UserImage::find($imageId);
            $image->delete();
        }

        return redirect()->back();

    }
}
