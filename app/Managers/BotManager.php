<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Services\GeocoderService;
use App\Services\UserLocationService;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

/**
 * Class BotManager
 * @package App\Managers
 */
class BotManager extends UserManager
{
    /** @var StorageManager */
    private $storageManager;

    /** @var User $user */
    private $user;
    /**
     * @var UserLocationService
     */
    private UserLocationService $userLocationService;

    /**
     * BotManager constructor.
     * @param User $user
     * @param StorageManager $storageManager
     */
    public function __construct(
        User $user,
        StorageManager $storageManager,
        UserLocationService $userLocationService
    ) {
        $this->user = $user;
        parent::__construct($this->user, $storageManager, $userLocationService);
        $this->userLocationService = $userLocationService;
    }

    /**
     * Receives an array tha contains the data for
     * the creation of the new Bot in the Database
     * and persists the new Bot's data to the
     * user table, role_user table and user_meta table
     *
     * @param array $botData
     * @throws \Exception
     */
    public function createBot(array $botData)
    {
        $botData = $this->buildBotArrayToPersist($botData, 'create');
        $botData['user']['created_by_id'] = \Auth::user()->getId();

        $this->persistUser($botData);
    }

    /**Alice in a court of justice before, but she remembered how small she was not a moment to think that will be When t…
     * @param array $botData
     * @param int $botId
     * @throws \Exception
     */
    public function updateBot(array $botData, int $botId)
    {
        $botData = $this->buildBotArrayToPersist($botData, 'update');
        $this->updateUser($botData, $botId);
    }

    /**
     * @param array $botData
     * @param string $action
     * @return array
     * @throws \Spatie\Geocoder\Exceptions\CouldNotGeocode
     */
    private function buildBotArrayToPersist(array $botData, string $action)
    {
        $usersTableData = Arr::where($botData, function ($value, $key) {
            return in_array(
                $key,
                array_merge(
                    UserConstants::userTableFields('bot', 'public'),
                    ['password', 'active']
                )
            );
        });

        $userMetaTableData = Arr::where($botData, function ($value, $key) {
            return in_array(
                $key,
                array_merge(
                    array_keys(UserConstants::selectableFields('bot')),
                    UserConstants::textFields('bot', 'public'),
                    UserConstants::textInputs('bot', 'all')
                )
            );
        });

        $userDataToPersist['user'] = $usersTableData;
        $userDataToPersist['user_meta'] = $userMetaTableData;



        if (isset($userDataToPersist['user_meta']['dob'])) {
            $userDataToPersist['user_meta']['dob'] = Carbon::parse($userDataToPersist['user_meta']['dob'])->format('Y-m-d');
        }

        if (isset($userDataToPersist['user_meta']['city'])) {
            $coordinates = $this->userLocationService->getCoordinatesForCity($userDataToPersist['user_meta']['city']);

            $userDataToPersist['user_meta']['lat'] = $coordinates['lat'];
            $userDataToPersist['user_meta']['lng'] = $coordinates['lng'];
        }

        if (isset($userDataToPersist['user_meta']['city'])) {
            $userDataToPersist['user_meta']['city'] = trim(ucfirst($userDataToPersist['user_meta']['city']));
        }

        if (empty($botData['user_images'][0])) {
            $userDataToPersist['user_images'] = [];
        } else {
            $userDataToPersist['user_images'] = $botData['user_images'];
        }

        if (!isset($botData['profile_image'])) {
            $userDataToPersist['profile_image'] = null;
        } else {
            $userDataToPersist['profile_image'] = $botData['profile_image'];
        }

        if ($action == 'create') {
            $userDataToPersist['user']['password'] = Hash::make($userDataToPersist['user']['password']);
            $userDataToPersist['user']['role'] = User::TYPE_BOT;
        }

        return $userDataToPersist;
    }
}
