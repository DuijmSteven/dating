<?php

namespace App\Http\Controllers\Api;

use App\Managers\UserImageManager;
use Illuminate\Http\Request;

class UserImageController
{
    /**
     * @var UserImageManager
     */
    private UserImageManager $userImageManager;

    public function __construct(
        UserImageManager $userImageManager
    )
    {
        $this->userImageManager = $userImageManager;
    }

    public function destroy(Request $request, int $userId, int $imageId)
    {
        try {
            $this->userImageManager->deleteImage($imageId);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function setProfile(Request $request, int $userId, int $imageId)
    {
        try {
            $this->userImageManager->setProfileImage($userId, $imageId);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function toggleVisibility(Request $request, int $userId, int $imageId)
    {
        try {
            $this->userImageManager->toggleImageVisibility($imageId);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
