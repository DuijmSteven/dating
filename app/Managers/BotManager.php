<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Helpers\ccampbell\ChromePhp\ChromePhp;
use App\User;
use App\UserMeta;
use Hash;

class BotManager extends UserBotManager
{
    /** @var User  */
    private $user;

    /**
     * BotManager constructor.
     * @param User $user
     * @param UploadManager $uploadManager
     */
    public function __construct(User $user, UploadManager $uploadManager)
    {
        $this->user = $user;
        parent::__construct($this->user, $uploadManager);
    }

    /**
     * Receives an array tha contains the data for
     * the creation of the new Bot in the Database
     * and persists the new Bot's data to the
     * user table, role_user table and user_meta table
     *
     * @param array $botData
     */
    public function createBot(array $botData)
    {
        $userData = $this->buildUserArrayToPersist($botData);
        $this->persistUser($userData);
    }

    public function updateBot(array $botData, $botId = 1)
    {
        $usersTableData = array_where($botData, function ($value, $key) {
            return in_array($key, UserConstants::BOT_USER_TABLE_FIELDS);
        });

        $user = User::findOrFail($botId);
        $user->update($usersTableData);

        $userMetaTableData = array_where($botData, function ($value, $key) {
            return in_array($key, array_keys(UserConstants::PROFILE_FIELDS));
        });

        UserMeta::where('user_id', $botId)->update($userMetaTableData);
    }

    /**
     * Receives an array with data of a user, admin or bot
     * that is to be created, and returns an array suitable
     * to be used to persist the data to the user table,
     * role_user table and user_meta table
     *
     * @param array $botData
     * @return array
     */
    private function buildUserArrayToPersist(array $botData)
    {
        $usersTableData = array_where($botData, function ($value, $key) {
            return in_array($key, UserConstants::USER_FIELDS);
        });

        $userMetaTableData = array_where($botData, function ($value, $key) {
            return in_array($key, array_keys(UserConstants::PROFILE_FIELDS));
        });

        $userDataToPersist = $usersTableData;
        $userDataToPersist['password'] = Hash::make($userDataToPersist['password']);
        $userDataToPersist['role'] = UserConstants::ROLES['bot'];
        $userDataToPersist['meta'] = $userMetaTableData;

        $userDataToPersist['active'] = 1;

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

        return $userDataToPersist;
    }
}
