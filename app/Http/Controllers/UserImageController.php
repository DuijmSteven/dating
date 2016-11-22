<?php

namespace App\Http\Controllers;


use App\Managers\StorageManager;
use App\Managers\UserImageManager;
use App\UserImage;
use Illuminate\Support\Facades\DB;

class UserImagesController extends Controller
{
    /** @var UserImageManager $userImageManager */
    private $userImageManager;

    /** @var StorageManager */
    private $storageManager;

    /**
     * UserImagesController constructor.
     * @param UserImageManager $userImageManager
     * @param StorageManager $storageManager
     */
    public function __construct(UserImageManager $userImageManager, StorageManager $storageManager)
    {
        $this->userImageManager = $userImageManager;
        $this->storageManager = $storageManager;
    }

    /**
     * @param $imageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $imageId)
    {
        if ($this->storageManager->deleteImage($imageId)) {
            $image = UserImage::find($imageId);
            $image->delete();
        }

        return redirect()->back();
    }

    /**
     * @param $imageId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function setProfileImage(int $imageId)
    {
        $this->userImageManager->setProfileImage($imageId);
        return redirect()->back();
    }

    public function toggleImageVisibility(int $imageId)
    {
        $this->userImageManager->toggleImageVisibility($imageId);
        return redirect()->back();
    }
}
