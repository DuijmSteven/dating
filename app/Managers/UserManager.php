<?php


namespace App\Managers;

use App\RoleUser;
use App\User;
use App\UserImage;
use App\UserMeta;
use Kim\Activity\Activity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;

class UserManager
{
    /** @var User */
    private $user;

    /** @var StorageManager  */
    private $storageManager;

    /**
     * HandlesUserDbInteractions constructor.
     * @param User $user
     * @param StorageManager $storageManager
     */
    public function __construct(User $user, StorageManager $storageManager)
    {
        $this->user = $user;
        $this->storageManager = $storageManager;
    }

    public function persistUser(array $userData)
    {
        $createdUser = $this->persistUserDetails($userData);

        if (isset($userData['profile_image']) && ($userData['profile_image'] instanceof UploadedFile)) {
            $this->persistUserProfileImage($userData['profile_image'], $createdUser->id);
        }

        if (isset($userData['user_images'])) {
            $this->persistUserImages($userData['user_images'], $createdUser->id);
        }
    }

    public function updateUser(array $userData)
    {
        \Log::info($userData);
        $createdUser = $this->updateUserDetails($userData);
\Log::info($createdUser);
        if (isset($userData['user_images'])) {
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

        $uploadedUserImagesFilename = $this->storageManager->saveUserPhoto($userProfileImage, $userId);

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
            $imageFilenames[] = $this->storageManager->saveUserPhoto($image, $userId);
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

    /**
     * @param $userData
     * @return mixed
     * @throws \Exception
     */
    private function updateUserDetails($userData)
    {
        DB::beginTransaction();
        try {
            $updatedUser = $this->user->update($userData);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $userMetaInstance = UserMeta::where('user_id', $updatedUser->id)->get();

            $userMetaTableData = array_where($userData, function ($value, $key) {
                return in_array($key, array_keys(\UserConstants::PROFILE_FIELDS));
            });

            $userMetaInstance->update($userMetaTableData);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        return $updatedUser;
    }


    /**
     * Only used in development to insert rows in the sessions
     * table for an amount of users
     *
     * @param $peasantAmount
     * @return mixed
     */
    public function setRandomUsersOnline($userAmount)
    {
        $randomUsers = $this->user->with(['roles' => function ($query) {
            $query->where('name', 'user');
            $query->select('name', 'user_id');
        }])->orderByRaw('RAND()')->take($userAmount)->get();

        // This method is nly used in dev env so it is ok to do this
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Model::unguard();

        foreach ($randomUsers as $user) {
            Session::create([
                'id' => md5(uniqid(rand(), true)),
                'user_id' => $user->id,
                'payload' => base64_encode('test'),
                'last_activity' => time()
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return $randomUsers;
    }

    /**
     * Retrieves collection of users that were online in the most recent
     * specified amount of minutes
     *
     * @param $minutes
     * @return User Collection
     */
    public function latestOnline($minutes)
    {
        $latestIds = Activity::users($minutes)->pluck('user_id')->toArray();

        return User::whereIn('id', $latestIds)->limit(\UserConstants::MAX_AMOUNT_ONLINE_TO_SHOW)->get();
    }
}
