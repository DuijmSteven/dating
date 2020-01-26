<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Peasants\PeasantUpdateImagesRequest;
use App\Managers\PeasantManager;
use App\Managers\StorageManager;
use App\Managers\UserImageManager;
use App\User;
use App\UserImage;
use Redirect;
use URL;

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
        PeasantManager $peasantManager
    ) {
        parent::__construct();
        $this->userImageManager = $userImageManager;
        $this->storageManager = $storageManager;
        $this->peasantManager = $peasantManager;
    }

    public function update(PeasantUpdateImagesRequest $peasantUpdateRequest)
    {
        $peasantUpdateRequest->formatInput();
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
                toast()->message(trans('edit_profile.image_limit_reached'), 'error');

                return redirect()->back();
            }
        }

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantUpdateRequest->route('userId'));

            toast()->message(trans('user_profile.feedback.profile_updated'), 'success');

        } catch (\Exception $exception) {
            \Log::error($exception);

            toast()->message(trans('user_profile.feedback.profile_not_updated'), 'error');
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
            $image = UserImage::findOrFail($imageId);
            $image->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }

        if ($this->storageManager->fileExists($image->filename, \StorageHelper::userImagesPath($image->user_id))) {

            $this->storageManager->deleteUserImage($image->user_id, $image->filename);
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
        $this->userImageManager->setProfileImage($userId, $imageId);
        return Redirect::to(URL::previous() . "#images-section");
    }

    public function toggleImageVisibility(int $imageId)
    {
        $this->userImageManager->toggleImageVisibility($imageId);
        return Redirect::to(URL::previous() . "#images-section");
    }
}
