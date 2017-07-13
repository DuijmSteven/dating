<?php


namespace App\Managers;

use App\Facades\Helpers\ApplicationConstants\UserConstants;
use App\RoleUser;
use App\Session;
use App\User;
use App\UserImage;
use App\UserMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kim\Activity\Activity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Class UserManager
 * @package App\Managers
 */
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

    /**
     * @param array $userData
     * @throws \Exception
     */
    public function persistUser(array $userData)
    {
        DB::beginTransaction();
        $createdUser = $this->persistUserDetails($userData);

        if (isset($userData['profile_image']) && ($userData['profile_image'] instanceof UploadedFile)) {
            $this->persistProfileImage($userData['profile_image'], $createdUser->id);
        }

        if (isset($userData['user_images'])) {
            $this->persistImages($userData['user_images'], $createdUser->id);
        }
        DB::commit();
    }

    /**
     * @param array $userData
     * @param int $userId
     * @throws \Exception
     */
    public function updateUser(array $userData, int $userId)
    {
        DB::beginTransaction();
        $this->updateUserDetails($userData, $userId);

        if (isset($userData['user_images'])) {
            $this->persistImages($userData['user_images'], $userId);
        }
        DB::commit();
    }

    /**
     * @param array $userImages
     * @param int $userId
     * @throws \Exception
     */
    private function persistImages(array $userImages, $userId = 1)
    {
        if (!empty($userImages)) {
            try {
                $imageFilenames = $this->uploadImages($userImages, $userId);
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }

            foreach ($imageFilenames as $filename) {
                $userImage = new UserImage([
                    'user_id' => $userId,
                    'filename' => $filename,
                    'visible' => 0,
                    'profile' => 0
                ]);

                try {
                    $userImage->save();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    throw $exception;
                }
            }
        }
    }

    /**
     * @param UploadedFile $userProfileImage
     * @param int $userId
     * @throws \Exception
     */
    private function persistProfileImage(UploadedFile $userProfileImage, int $userId)
    {
        UserImage::where('user_id', $userId)->get();

        try {
            $uploadedUserImageFilename = $this->storageManager->saveUserPhoto($userProfileImage, $userId);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        $userImage = new UserImage([
            'user_id' => $userId,
            'filename' => $uploadedUserImageFilename,
            'visible' => 1,
            'profile' => 1
        ]);

        try {
            $userImage->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function uploadImages(array $userImages, int $userId)
    {
        $imageFilenames = [];

        foreach ($userImages as $image) {
            try {
                $imageFilenames[] = $this->storageManager->saveUserPhoto($image, $userId);
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        }
        return $imageFilenames;
    }

    /**
     * @param $userData
     * @return mixed
     * @throws \Exception
     */
    private function persistUserDetails(array $userData)
    {
        try {
            $createdUser = $this->user->create($userData['user']);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $userMetaInstance = new UserMeta(array_merge(
                $userData['user_meta'],
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
                'role_id' => $userData['user']['role'],
                'user_id' => $createdUser->id
            ]);

            $roleUserInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $createdUser;
    }

    /**
     * @param array $userData
     * @param int $userId
     * @return bool
     * @throws \Exception
     */
    private function updateUserDetails(array $userData, int $userId)
    {
        try {
            $this->user->where('id', $userId)->update($userData['user']);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $userMetaInstance = UserMeta::where('user_id', $userId)->first();
            $userMetaInstance->update($userData['user_meta']);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return true;
    }

    /**
     * @param $userAmount
     * @return mixed
     */
    public function setRandomUsersOnline($userAmount)
    {
        $randomUsers = $this->user->with(['roles', 'meta'])
            ->whereHas('roles', function ($query) {
                $query->whereIn('id', [2, 3]);
            })
            ->orderByRaw('RAND()')->take($userAmount)
            ->get();

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
     * @param int $minutes
     * @param string $gender
     * @internal param $
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function latestOnline(int $minutes, string $gender = 'any')
    {
        $latestIds = Activity::users($minutes)->pluck('user_id')->toArray();

        $query = User::with('meta')->whereIn('id', $latestIds);

        if ($gender !== 'any') {
            $query = $query->whereHas('meta', function ($query) {
                $query->where('gender', \UserConstants::selectableField('gender', 'common', 'array_flip')[$gender]);
            });
        }
        return User::with('meta')->whereIn('id', $latestIds)->limit(\UserConstants::getMaxAmountOnline())->get();
    }

    /**
     * @param int $userId
     * @throws \Exception
     */
    public function deleteUser(int $userId)
    {
        $user = $this->user->with(['images'])->findOrFail($userId);
        
        DB::beginTransaction();
        try {
            $user->delete();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            UserMeta::where('user_id', $userId)->delete();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            RoleUser::where('user_id', $userId)->delete();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        foreach ($user->images as $image) {
            $this->storageManager->deleteImage($image->user_id, $image->filename);
        }
    }

    /**
     * @return User
     */
    public static function getAndFormatAuthenticatedUser()
    {
        $user = Auth::user();

        if (!($user instanceof User)) {
            return null;
        }

        $user = User::with('profileImage', 'images', 'meta')->where('id', $user->getId())->get()[0];

        return $user;
    }
}
