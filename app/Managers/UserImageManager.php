<?php


namespace App\Managers;

use App\UserImage;
use Illuminate\Support\Facades\DB;

class UserImageManager
{
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
}
