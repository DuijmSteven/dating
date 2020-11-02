<?php


namespace App\Managers;

use App\Facades\Helpers\StorageHelper;
use App\UserImage;
use Illuminate\Support\Facades\DB;

class UserImageManager
{
    /**
     * @var StorageManager
     */
    private StorageManager $storageManager;

    public function __construct(
        StorageManager $storageManager
    )
    {
        $this->storageManager = $storageManager;
    }

    public function setProfileImage(int $userId, int $imageId)
    {
        DB::beginTransaction();
        try {
            UserImage::where('user_id', $userId)->where('profile', 1)->update(['profile' => 0]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            UserImage::where('id', $imageId)->update(['profile' => 1, 'visible' => 1]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
    }

    public function toggleImageVisibility(int $imageId)
    {
        $image = UserImage::findOrFail($imageId);

        if ($image->profile) {
            abort(403, 'Profile photo visibility cannot be toggled');
        }
        $image->visible = ! $image->visible;

        try {
            return $image->save();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function deleteImage(int $imageId)
    {
        DB::beginTransaction();

        $image = UserImage::findOrFail($imageId);
        $image->delete();

        if ($this->storageManager->fileExists($image->filename, StorageHelper::userImagesPath($image->user_id))) {
            $this->storageManager->deleteUserImage($image->user_id, $image->filename);
        }

        DB::commit();
    }
}
