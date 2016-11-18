<?php

namespace App\Managers;

use App\Bot;
use App\BotMeta;
use Illuminate\Support\Facades\Hash;

class BotManager extends UserManager
{
    /** @var Bot  */
    private $bot;

    /** @var StorageManager */
    private $storageManager;

    /**
     * BotManager constructor.
     * @param Bot $bot
     * @param StorageManager $storageManager
     */
    public function __construct(Bot $bot, StorageManager $storageManager)
    {
        $this->bot = $bot;
        parent::__construct($this->bot, $storageManager);
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
        $botData = $this->buildBotArrayToPersist($botData, 'create');
        $this->persistUser($botData);
    }

    public function updateBot(array $botData, int $botId)
    {
        $botData = $this->buildBotArrayToPersist($botData, 'update');

        $this->updateUser($botData, $botId);
    }

    /**
     * @param array $botData
     * @param string $action
     * @return array
     */
    private function buildBotArrayToPersist(array $botData, string $action)
    {
        $usersTableData = array_where($botData, function ($value, $key) {
            return in_array($key, \UserConstants::USER_FIELDS);
        });

        $userMetaTableData = array_where($botData, function ($value, $key) {
            return in_array($key, array_keys(\UserConstants::PROFILE_FIELDS));
        });

        $userDataToPersist['user'] = $usersTableData;
        $userDataToPersist['user_meta'] = $userMetaTableData;

        $userDataToPersist['user']['password'] = Hash::make($userDataToPersist['user']['password']);
        $userDataToPersist['user']['active'] = 1;

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
            $userDataToPersist['user']['role'] = \UserConstants::ROLES['bot'];
        }

        return $userDataToPersist;
    }
}
