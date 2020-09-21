<?php


namespace App\Managers;

use App\EmailType;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Mail\ProfileCompletion;
use App\Mail\ProfileViewed;
use App\RoleUser;
use App\Services\GeocoderService;
use App\Services\UserLocationService;
use App\Session;
use App\User;
use App\UserAccount;
use App\UserEmailTypeInstance;
use App\UserImage;
use App\UserMeta;
use App\UserView;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Kim\Activity\Activity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @var UserLocationService
     */
    private UserLocationService $userLocationService;

    /**
     * HandlesUserDbInteractions constructor.
     * @param User $user
     * @param StorageManager $storageManager
     */
    public function __construct(
        User $user,
        StorageManager $storageManager,
        UserLocationService $userLocationService
    ) {
        $this->user = $user;
        $this->storageManager = $storageManager;
        $this->userLocationService = $userLocationService;
    }

    /**
     * @param User $user
     * @return array
     */
    public static function getRequiredRelationsAndCountsForRole(int $roleId): array
    {
        if ($roleId === User::TYPE_ADMIN) {
            $relations = array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::ADMIN_RELATIONS,
//                    User::OPERATOR_RELATIONS,
//                    User::EDITOR_RELATIONS
                )
            );

            $relationCounts = array_merge(
                User::ADMIN_RELATION_COUNTS,
//                User::OPERATOR_RELATION_COUNTS,
//                User::EDITOR_RELATION_COUNTS
            );
        } elseif ($roleId === User::TYPE_EDITOR) {
            $relations = array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::EDITOR_RELATIONS
                )
            );

            $relationCounts = array_merge(
                User::EDITOR_RELATION_COUNTS
            );
        } elseif ($roleId === User::TYPE_OPERATOR) {
            $relations = array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::OPERATOR_RELATIONS
                )
            );

            $relationCounts = array_merge(
                User::OPERATOR_RELATION_COUNTS
            );
        } elseif ($roleId === User::TYPE_PEASANT) {
            $relations = array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::PEASANT_RELATIONS
                )
            );

            $relationCounts = array_merge(
                User::PEASANT_RELATION_COUNTS
            );
        } elseif ($roleId === User::TYPE_BOT) {
            $relations = array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::BOT_RELATIONS
                )
            );

            $relationCounts = array_merge(
                User::BOT_RELATION_COUNTS
            );
        }
        return array($relations, $relationCounts);
    }

    public function getUserCredits(int $userId)
    {
        return $this->user->find($userId)->account->credits;
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

        if (isset($userData['profile_image']) && ($userData['profile_image'] instanceof UploadedFile)) {
            $profileImage = User::find($userId)->profileImage;

            if ($profileImage instanceof UserImage) {
                $profileImage->profile = 0;
                $profileImage->save();
            }

            $this->persistProfileImage($userData['profile_image'], $userId);
        }

        if (isset($userData['user_images'])) {
            $this->persistImages($userData['user_images'], $userId);
        }

        if (isset($userData['email_notifications'])) {

            $user = User::with('emailTypes')->where('id', $userId)->get()[0];
            $user->emailTypes()->detach();

            foreach ($userData['email_notifications'] as $emailTypeId) {
                $user->emailTypes()->attach($emailTypeId);
            }
        }
        DB::commit();
    }

    public function storeProfileView(
        User $viewer,
        User $viewed,
        int $type = null,
        Carbon $createdAt = null
    ) {
        $userViewInstance = new UserView();
        $userViewInstance->setViewerId($viewer->getId());
        $userViewInstance->setViewedId($viewed->getId());
        $userViewInstance->setType($type);

        if ($createdAt) {
            $userViewInstance->setCreatedAt($createdAt);
        }

        $userViewInstance->save();
    }

    public function setProfileViewedEmail(User $viewed, User $viewer = null, $delay = 0) {
        $userEmailTypeIds = $viewed->emailTypes()->get()->pluck('id')->toArray();

        $profileViewedEmailEnabled = in_array(
            EmailType::PROFILE_VIEWED,
            $userEmailTypeIds
        );

        $viewerUser = null;

        if ($profileViewedEmailEnabled && $viewed->isPeasant()) {

            if (!($viewer instanceof User)) {
                $bot = User::where('active', true)
                    ->whereHas('meta', function ($query) use ($viewed) {
                        $query->where('looking_for_gender', $viewed->meta->gender);
                        $query->where('gender', $viewed->meta->looking_for_gender);
                    })
                    ->whereHas('roles', function ($query) {
                        $query->where('id', User::TYPE_BOT);
                    })
                    ->orderBy(\DB::raw('RAND()'))
                    ->first();

                if ($bot instanceof User) {
                    $viewerUser = $bot;
                }
            } else {
                $viewerUser = $viewer;
            }

            if ($viewerUser instanceof User) {

                if (
                    config('app.env') === 'production' ||
                    config('app.env') === 'local' &&
                    $viewed->isMailable
                ) {
                    $profileViewedEmail = (new ProfileViewed(
                        $viewerUser,
                        $viewed,
                        $viewerUser,
                        new Carbon('now')
                    ))
                    ->delay($delay)
                    ->onQueue('emails');

                    Mail::to($viewed)
                        ->queue($profileViewedEmail);
                }

                $userEmailTypeInstance = new UserEmailTypeInstance();
                $userEmailTypeInstance->setEmail($viewed->getEmail());
                $userEmailTypeInstance->setActorId($viewerUser->getId());
                $userEmailTypeInstance->setReceiverId($viewed->getId());
                $userEmailTypeInstance->setEmailTypeId(EmailType::PROFILE_VIEWED);
                $userEmailTypeInstance->save();
            }
        }

        return $viewerUser;
    }

    public function setProfileCompletionEmail(User $user)
    {
        $profileCompletionEmail = (new ProfileCompletion($user))->onQueue('emails');
        Mail::to($user)->queue($profileCompletionEmail);

        $userEmailTypeInstance = new UserEmailTypeInstance();
        $userEmailTypeInstance->setEmail($user->getEmail());
        $userEmailTypeInstance->setReceiverId($user->getId());
        $userEmailTypeInstance->setEmailTypeId(EmailType::PROFILE_COMPLETION);

        $userEmailTypeInstance->save();
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

    public function createUser(array $userData)
    {
        $userData = $this->buildUserArrayToPersist($userData, 'create');
        $userData['user']['created_by_id'] = \Auth::user()->getId();

        $this->persistUser($userData);
    }

    /**
     * @param array $peasantData
     * @param string $action
     * @return array
     * @throws \Spatie\Geocoder\Exceptions\CouldNotGeocode
     */
    private function buildUserArrayToPersist(array $userData, string $action)
    {
        $usersTableData = Arr::where($userData, function ($value, $key) {
            return in_array(
                $key,
                array_merge(
                    UserConstants::userTableFields('peasant', 'public'),
                    ['password', 'active']
                )
            );
        });

        $userMetaTableData = Arr::where($userData, function ($value, $key) {
            return in_array(
                $key,
                array_merge(
                    array_keys(UserConstants::selectableFields('peasant')),
                    UserConstants::textFields('peasant', 'public'),
                    UserConstants::textInputs('peasant', 'all')
                )
            );
        });

        $userDataToPersist['user'] = $usersTableData;
        $userDataToPersist['user_meta'] = $userMetaTableData;

        if (isset($userDataToPersist['user_meta']['dob'])) {
            $userDataToPersist['user_meta']['dob'] = Carbon::parse($userDataToPersist['user_meta']['dob'])->format('Y-m-d');
        }

        if (isset($userDataToPersist['user_meta']['city']) && strlen($userDataToPersist['user_meta']['city']) > 0) {
            $coordinates = $this->userLocationService->getCoordinatesForCity($userDataToPersist['user_meta']['city']);

            $userDataToPersist['user_meta']['lat'] = $coordinates['lat'];
            $userDataToPersist['user_meta']['lng'] = $coordinates['lng'];
        } else {
            $userDataToPersist['user_meta']['city'] = null;
        }

        if (empty($userData['user_images'][0])) {
            $userDataToPersist['user_images'] = [];
        } else {
            $userDataToPersist['user_images'] = $userData['user_images'];
        }

        if (!isset($userData['profile_image'])) {
            $userDataToPersist['profile_image'] = null;
        } else {
            $userDataToPersist['profile_image'] = $userData['profile_image'];
        }

        if ($action == 'create') {
            $userDataToPersist['user']['password'] = Hash::make($userDataToPersist['user']['password']);

            if (isset($userData['user']['role'])) {
                $userDataToPersist['user']['role'] = $userData['user']['role'];
            }
        }

        if ($action == 'update') {
            if (isset($userData['email_notifications'])) {
                $userDataToPersist['email_notifications'] = $userData['email_notifications'];
            } else {
                $userDataToPersist['email_notifications'] = [];
            }
        }

        return $userDataToPersist;
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

        if ($userData['user']['role'] === User::TYPE_PEASANT) {
            try {
                $amountOfFreeCredits = 1;

                /** @var UserAccount $userAccountInstance */
                $userAccountInstance = new UserAccount([
                    'user_id' => $createdUser->id,
                    'credits' => $amountOfFreeCredits
                ]);

                $userAccountInstance->save();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
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
     * @param $botAmount
     */
    public function setRandomBotsOnline(int $botAmount, $gender = User::GENDER_FEMALE) : void
    {
        $randomUsers = $this->user->with(['roles', 'meta'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) use ($gender) {
                $query->where('gender', $gender);
                $query->where('looking_for_gender', User::GENDER_MALE);
            })
            ->where('active', true)
            ->orderByRaw('RAND()')
            ->take($botAmount)
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
    public function latestOnline(int $minutes, $limit = 20)
    {
        $latestIds = Activity::users($minutes)->pluck('user_id')->toArray();

        $query = User::with('meta', 'profileImage')
            ->whereIn('id', $latestIds)
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
                $query->orWhere('id', User::TYPE_BOT);
            })
            ->whereNotIn('id', [Auth::user()->getId()]);

        $query = $query->whereHas('meta', function ($query) {
            $query->where('gender', Auth::user()->meta->getLookingForGender());
            $query->where('looking_for_gender', Auth::user()->meta->getGender());
        });

        return $query->limit($limit)->get();
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

        try {
            foreach ($user->images as $image) {
                $this->storageManager->deleteUserImage($image->user_id, $image->filename);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
    }

    /**
     * @return User
     */
    public static function getAndFormatAuthenticatedUser()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!($user instanceof User)) {
            return null;
        }

        [$relations, $relationCounts] = self::getRequiredRelationsAndCountsForRole($user->roles[0]->getId());

        $user = User::with($relations)
            ->withCount($relationCounts)
            ->where('id', $user->getId())->get()[0];

        return $user;
    }

    /**
     * @param int $userId
     * @return User|User[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     * @throws \Exception
     */
    public function getUserById(int $userId) {

        $user =  User::with('profileImage', 'images', 'meta')->find($userId);

        if (!($user instanceof User)) {
            throw new \Exception('This user does not exist in the system');
        }

        return $user;
    }
}
