<?php


namespace App\Managers;

use App\RoleUser;
use App\User;
use App\UserImage;
use App\UserMeta;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;

class UserBotManager
{
    /** @var User */
    private $user;

    /** @var UploadManager  */
    private $uploadManager;

    /**
     * HandlesUserDbInteractions constructor.
     * @param User $user
     * @param UploadManager $uploadManager
     */
    public function __construct(User $user, UploadManager $uploadManager)
    {
        $this->user = $user;
        $this->uploadManager = $uploadManager;
    }

    public function persistUser(array $userData)
    {
        $createdUser = $this->persistUserDetails($userData);

        if (isset($userData['profile_image']) && ($userData['profile_image'] instanceof UploadedFile)) {
            $this->persistUserProfileImage($userData['profile_image'], $createdUser->id);
        }

        if (isset($userData['profile_image'])) {
            $this->persistUserImages($userData['user_images'], $createdUser->id);
        }
    }

    private function persistUserImages(array $userImages, $userId = 1)
    {
        if (empty($userImages)) {
            return [];
        }

        $uploadedUserImagesFilenames = $this->uploadUserImagesToCloud($userImages, $userId);

        foreach ($uploadedUserImagesFilenames as $filename) {
            $userImage = new UserImage([
                'user_id' => $userId,
                'filename' => $filename,
                'visible' => 1
            ]);

            $userImage->save();
        }
    }

    private function persistUserProfileImage(UploadedFile $userProfileImage, $userId = 1)
    {
        UserImage::where('user_id', $userId)->get();

        $uploadedUserImagesFilename = $this->uploadManager->saveUserPhoto($userProfileImage, $userId);

        $userImage = new UserImage([
            'user_id' => $userId,
            'filename' => $uploadedUserImagesFilename,
            'visible' => 1,
            'profile' => 1
        ]);

        $userImage->save();
    }

    private function uploadUserImagesToCloud(array $userImages, $userId = 1)
    {
        $imageFilenames = [];

        foreach ($userImages as $image) {
            $imageFilenames[] = $this->uploadManager->saveUserPhoto($image, $userId);
        }
        return $imageFilenames;
    }

    /**
     * @param $userData
     * @return mixed
     * @throws \Exception
     */
    private function persistUserDetails($userData)
    {
        DB::beginTransaction();
        try {
            $createdUser = $this->user->create($userData);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $userMetaInstance = new UserMeta(array_merge(
                $userData['meta'],
                ['user_id' => $createdUser->id]
            ));

            $userMetaInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            /** @var RoleUser $roleUserInstance */
            $roleUserInstance = new RoleUser([
                'role_id' => $userData['role'],
                'user_id' => $createdUser->id
            ]);

            $roleUserInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $createdUser;
    }
}
