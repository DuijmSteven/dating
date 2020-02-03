<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Services\GeocoderService;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class PeasantManager extends UserManager
{
    /** @var User  */
    private $user;

    /** @var StorageManager */
    private $storageManager;

    /**
     * PeasantManager constructor.
     * @param User $user
     * @param StorageManager $storageManager
     */
    public function __construct(User $user, StorageManager $storageManager)
    {
        $this->user = $user;
        parent::__construct($this->user, $storageManager);
    }

    /**
     * Receives an array tha contains the data for
     * the creation of the new Peasant in the Database
     * and persists the new Peasant's data to the
     * user table, role_user table and user_meta table
     *
     * @param array $peasantData
     */
    public function createPeasant(array $peasantData)
    {
        $peasantData = $this->buildPeasantArrayToPersist($peasantData, 'create');
        $this->persistUser($peasantData);
    }

    /**
     * @param array $peasantData
     * @param int $peasantId
     * @throws \Exception
     */
    public function updatePeasant(array $peasantData, int $peasantId)
    {
        $peasantData = $this->buildPeasantArrayToPersist($peasantData, 'update');
        $this->updateUser($peasantData, $peasantId);
    }

    /**
     * @param array $peasantData
     * @param string $action
     * @return array
     * @throws \Spatie\Geocoder\Exceptions\CouldNotGeocode
     */
    private function buildPeasantArrayToPersist(array $peasantData, string $action)
    {
        $usersTableData = Arr::where($peasantData, function ($value, $key) {
            return in_array(
                $key,
                array_merge(
                    UserConstants::userTableFields('peasant', 'public'),
                    ['password', 'active']
                )
            );
        });

        $userMetaTableData = Arr::where($peasantData, function ($value, $key) {
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

        $userDataToPersist['user']['active'] = 1;

        $client = new Client();
        $geocoder = new GeocoderService($client);

        $coordinates = $geocoder->getCoordinatesForAddress($userDataToPersist['user_meta']['city']);

        $userDataToPersist['user_meta']['lat'] = $coordinates['lat'];
        $userDataToPersist['user_meta']['lng'] = $coordinates['lng'];

        if (empty($peasantData['user_images'][0])) {
            $userDataToPersist['user_images'] = [];
        } else {
            $userDataToPersist['user_images'] = $peasantData['user_images'];
        }

        if (!isset($peasantData['profile_image'])) {
            $userDataToPersist['profile_image'] = null;
        } else {
            $userDataToPersist['profile_image'] = $peasantData['profile_image'];
        }

        if ($action == 'create') {
            $userDataToPersist['user']['password'] = Hash::make($userDataToPersist['user']['password']);
            $userDataToPersist['user']['role'] = UserConstants::selectableField('role', 'peasant', 'array_flip')['peasant'];
        }

        if ($action == 'update') {
            if (isset($peasantData['email_notifications'])) {
                $userDataToPersist['email_notifications'] = $peasantData['email_notifications'];
            } else {
                $userDataToPersist['email_notifications'] = [];
            }
        }

        return $userDataToPersist;
    }
}
