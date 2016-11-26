<?php

namespace App\Http\Controllers;


use App\Managers\StorageManager;
use App\Managers\UserImageManager;
use App\UserImage;
use Illuminate\Support\Facades\DB;

class UserImageController extends Controller
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
     * @param int $imageId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(int $imageId)
    {
        try {
            $image = UserImage::findOrFail($imageId);
            $image->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }

        $this->storageManager->deleteImage($image->user_id, $image->filename);

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
