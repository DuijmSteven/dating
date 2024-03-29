<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Peasants\PeasantUpdateImagesRequest;
use App\Managers\PeasantManager;
use App\Managers\StorageManager;
use App\Managers\UserImageManager;
use App\Services\UserActivityService;
use App\User;
use App\UserImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class UserImageController extends Controller
{
    /** @var UserImageManager $userImageManager */
    private $userImageManager;

    /** @var StorageManager */
    private $storageManager;

    /** @var PeasantManager  */
    private $peasantManager;

    /**
     * UserImageController constructor.
     * @param UserImageManager $userImageManager
     * @param StorageManager $storageManager
     * @param PeasantManager $peasantManager
     */
    public function __construct(
        UserImageManager $userImageManager,
        StorageManager $storageManager,
        PeasantManager $peasantManager,
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
        $this->userImageManager = $userImageManager;
        $this->storageManager = $storageManager;
        $this->peasantManager = $peasantManager;
    }

    public function update(PeasantUpdateImagesRequest $peasantUpdateRequest)
    {
        $peasantData = $peasantUpdateRequest->all();

        if (isset($peasantData['user_images']) || isset($peasantData['profile_image'])) {
            /** @var User $peasant */
            $peasant = User::find($peasantUpdateRequest->route('userId'));

            $peasantImagesCount = $peasant->images->count();

            $uploadedImagesCount = 0;

            if (isset($peasantData['user_images'])) {
                $uploadedImagesCount += count($peasantData['user_images']);
            }

            if (isset($peasantData['profile_image'])) {
                $uploadedImagesCount += 1;
            }

            if ($peasantImagesCount + $uploadedImagesCount > 10) {
                toastr()->error(trans(config('app.directory_name') . '/edit_profile.image_limit_reached'));
                return redirect()->back();
            }
        }

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantUpdateRequest->route('userId'));
            toastr()->success(trans(config('app.directory_name') . '/user_profile.feedback.profile_updated'));
        } catch (\Exception $exception) {
            \Log::error($exception);
            toastr()->error(trans(config('app.directory_name') . '/user_profile.feedback.profile_not_updated'));
        }

        return redirect()->back();
    }

    /**
     * @param int $imageId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(int $imageId)
    {
        try {
            $this->userImageManager->deleteImage($imageId);

            toastr()->success(trans(config('app.directory_name') . '/user_profile.feedback.profile_updated'));
        } catch (\Exception $exception) {
            DB::rollBack();
            toastr()->error(trans(config('app.directory_name') . '/user_profile.feedback.profile_not_updated'));
        }

        return Redirect::to(URL::previous() . "#images-section");
    }

    /**
     * @param $imageId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function setProfileImage(int $userId, int $imageId)
    {
        try {
            $this->userImageManager->setProfileImage($userId, $imageId);
            toastr()->success(trans(config('app.directory_name') . '/user_profile.feedback.profile_updated'));
        } catch (\Exception $exception) {
            toastr()->error(trans(config('app.directory_name') . '/user_profile.feedback.profile_not_updated'));
        }

        return Redirect::to(URL::previous() . "#images-section");
    }

    public function toggleImageVisibility(int $imageId)
    {
        $this->userImageManager->toggleImageVisibility($imageId);
        return Redirect::to(URL::previous() . "#images-section");
    }
}
