<?php

namespace App\Http\Controllers;


use App\Managers\StorageManager;
use App\UserImage;
use Illuminate\Support\Facades\DB;

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

    public function setProfileImage($imageId)
    {
        DB::beginTransaction();
        try {
            UserImage::where('profile', 1)->update(['profile' => 0]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            UserImage::where('id', $imageId)->update(['profile' => 1]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();

        return redirect()->back();
    }
}
