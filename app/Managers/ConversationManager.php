<?php

namespace App\Managers;

use App\Conversation;
use App\ConversationMessage;
use App\Helpers\ApplicationConstants\UserConstants;
use App\MessageAttachment;
use App\User;
use Carbon\Carbon;
use Cassandra\Type\UserType;
use Illuminate\Support\Facades\DB;

/**
 * Class ConversationManager
 * @package App\Managers
 */
class ConversationManager
{
    const CONVERSATION_LOCKING_TIME = 4;

    /** @var Conversation */
    private $conversation;

    /** @var StorageManager */
    private $storageManager;
    /**
     * @var ConversationMessage
     */
    private $conversationMessage;

    /**
     * ConversationManager constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(
        Conversation $conversation,
        ConversationMessage $conversationMessage,
        StorageManager $storageManager
    ) {
        $this->conversation = $conversation;
        $this->storageManager = $storageManager;
        $this->conversationMessage = $conversationMessage;
    }

    /**
     * @param array $messageData
     * @return ConversationMessage
     * @throws \Exception
     */
    public function createMessage(array $messageData)
    {
        $hasAttachment = isset($messageData['attachment']);

        try {
            $conversation = $this->createOrRetrieveConversation(
                $messageData['sender_id'],
                $messageData['recipient_id']
            );
        } catch (\Exception $exception) {
            throw $exception;
        }

        try {
            $messageInstance = new ConversationMessage([
                'conversation_id' => $conversation->getId(),
                'type' => 'generic',
                'sender_id' => $messageData['sender_id'],
                'recipient_id' => $messageData['recipient_id'],
                'body' => $messageData['message'],
                'has_attachment' => $hasAttachment,
                'operator_id' => isset($messageData['operator_id']) ? $messageData['operator_id'] : null
            ]);

            $messageInstance->save();
        } catch (\Exception $exception) {
            \Log::info(__CLASS__ . ' - ' . $exception->getMessage());
            throw $exception;
        }

        if ($hasAttachment) {
            try {

                $uploadedImageFilename = $this->storageManager->saveConversationImage(
                    $messageData['attachment'],
                    $conversation->getId()
                );

                $messageAttachment = new MessageAttachment([
                    'conversation_id' => $conversation->getId(),
                    'message_id' => $messageInstance->id,
                    'filename' => $uploadedImageFilename,
                ]);

                $messageAttachment->save();
            } catch (\Exception $exception) {
                throw $exception;
            }
        }

        return $messageInstance;
    }

    /**
     * @param string $age
     * @param string $lastMessageUserRoles
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getPaginated(
        string $age = 'any',
        string $lastMessageUserRoles = 'any',
        int $limit = 0,
        int $offset = 0
    ) {
        return self::conversationsByIds(
            $this->conversationIds($age, $lastMessageUserRoles, [], $limit, $offset),
            ['user_meta']
        );
    }

    /**
     * @return array
     */
    public function newPeasantBotConversations()
    {
        $conversations = Conversation::with(['userA', 'userB', 'messages'])
            ->whereDoesntHave('messages.sender.roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('userA.roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('userB.roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->where(function ($query) {
                $query->where('locked_at', null)
                    ->orWhere('locked_at', '<', Carbon::now()->subMinutes(4));
            })
            ->withTrashed()
            ->get();

        return $conversations;
    }

    /**
     * @return array
     */
    public function unrepliedPeasantBotConversations()
    {
        $conversations = Conversation::with(['userA', 'userB', 'messages'])
            ->with(['messages' => function ($query) {
                $query->orderBy('id', 'desc');
            }])
            ->has('messages', '>', '1')
            ->where(function ($query) {
                $query->where('locked_at', null)
                    ->orWhere('locked_at', '<', Carbon::now()->subMinutes(4));
            })
            ->withTrashed()
            ->get()
            ->filter(function ($value, $key) {

                if ($value->messages[0]->sender && $value->messages[0]->recipient) {

                return $value->messages[0]->sender->roles[0]->id === User::TYPE_PEASANT &&
                    $value->messages[0]->recipient->roles[0]->id === User::TYPE_BOT;
                } else {
                    return false;
                }
            });

        return $conversations;
    }

    /**
     * @param string $age
     * @param string $lastMessageUserRoles
     * @param array $types
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Exception
     */
    public function conversationIds(
        string $age = 'any',
        string $lastMessageUserRoles = 'any',
        array $types = [],
        int $limit = 0,
        int $offset = 0
    ) {
        $conversationIds = [];
        list($roleQuery, $ageQuery, $typesQuery) = $this->resolveConversationTypeOptions(
            $age,
            $lastMessageUserRoles,
            $types,
            true
        );

        $query = 'SELECT DISTINCT (c.id) as conversation_id
                    FROM conversations as c
                    JOIN conversation_messages cm ON cm.conversation_id = c.id
                    JOIN conversation_messages lm
                        ON lm.id =
                        (
                            SELECT mi.id
                            FROM conversation_messages mi
                            WHERE mi.conversation_id = c.id
                            ORDER BY mi.created_at DESC
                            LIMIT 1
                        )
                    JOIN users sender ON sender.id = lm.sender_id
                    JOIN users recipient ON recipient.id = lm.recipient_id
                    JOIN role_user sender_role ON sender_role.user_id = lm.sender_id
                    JOIN role_user recipient_role ON recipient_role.user_id = lm.recipient_id
                    WHERE (c.locked_at IS NULL OR c.locked_at < NOW() - INTERVAL ' . self::CONVERSATION_LOCKING_TIME . ' MINUTE) 
                    ' . $roleQuery .
            $typesQuery .
            $ageQuery .
            ' ORDER BY c.id DESC ';

        if ($limit) {
            $query .= ' LIMIT ' . $limit . ' ';
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset . ' ';
        }

        $results = \DB::select($query);

        foreach ($results as $result) {
            $conversationIds[] = $result->conversation_id;
        }

        return $conversationIds;
    }

    public function conversationsByIds(array $conversationIds, $options = [])
    {
        $allowedOptions = ['user_meta', 'profile_image', 'other_images'];

        $conversations = [];

        if (empty($conversationIds)) {
            return $conversations;
        }

        $userMetaFields = '';
        $userMetaJoin = '';
        if (in_array('user_meta', $options)) {
            $userMetaFields = ' ,user_a_meta.gender as user_a_gender, user_b_meta.gender as user_b_gender ';
            $userMetaJoin = ' JOIN user_meta user_a_meta ON user_a_meta.user_id = c.user_a_id
                              JOIN user_meta user_b_meta ON user_b_meta.user_id = c.user_b_id';
        }

        $query = 'SELECT  c.id as conversation_id, c.created_at as conversation_created_at, c.deleted_at as conversation_deleted_at,
                          m.id as last_message_id, m.created_at as last_message_created_at, m.body as last_message_body,
                          m.has_attachment as last_message_has_attachment, m.type as last_message_type,
                          m.sender_id as last_message_sender_id, m.recipient_id as last_message_recipient_id,
                          user_a.id as user_a_id, user_b.id as user_b_id, user_a.username as user_a_username,
                          user_b.username as user_b_username,
                          user_a_images.filename as user_a_profile_img, user_b_images.filename as user_b_profile_img, 
                          user_a_role.role_id as user_a_role_id, user_b_role.role_id as user_b_role_id
                          ' . $userMetaFields . '
                    FROM    conversations c
                    JOIN    conversation_messages m
                        ON      m.id =
                            (
                                SELECT  mi.id
                                FROM    conversation_messages mi
                                WHERE   mi.conversation_id = c.id
                                ORDER BY mi.created_at DESC
                                LIMIT 1
                            )
                    JOIN    users user_a ON user_a.id = c.user_a_id
                    JOIN    role_user user_a_role ON c.user_a_id = user_a_role.user_id
                    JOIN    role_user user_b_role ON c.user_b_id = user_b_role.user_id
                    JOIN    users user_b ON user_b.id = c.user_b_id
                    ' . $userMetaJoin . '
                    LEFT JOIN    user_images user_a_images
                        ON      user_a_images.id =
                            (
                                SELECT  ui.id
                                FROM    user_images ui
                                WHERE   ui.profile = 1 AND ui.user_id = user_a.id
                                LIMIT 1
                            )
                    LEFT JOIN    user_images user_b_images
                      ON      user_b_images.id =
                          (
                              SELECT  ui.id
                              FROM    user_images ui
                              WHERE   ui.profile = 1 AND ui.user_id = user_b.id
                              LIMIT 1
                          )
                    WHERE c.id IN (' . implode(',', $conversationIds) . ')
                    ORDER BY c.id DESC ';

        $results = \DB::select($query);

        $query = DB::table('conversations')->select(
            'conversations.id as conversation_id',
            'conversations.created_at as conversation_created_at',
            'conversation_messages.id as last_message_id',
            'conversation_messages.created_at as last_message_created_at',
            'conversation_messages.body as last_message_body',
            'conversation_messages.has_attachment as last_message_has_attachment',
            'conversation_messages.type as last_message_type',
            'conversation_messages.recipient_id as last_message_recipient_id'
        )
            ->join('conversation_messages', function ($join) {
                $join->on('conversation_messages.id', '=', DB::raw('(SELECT  mi.id
                FROM    conversation_messages mi
                WHERE   mi.conversation_id = conversations.id
                ORDER BY mi.created_at DESC
                LIMIT 1)'));
            })->get();

        $conversations = $this->formatConversations($results, $options);

        return $conversations;
    }

    /**
     * @return array
     */
    public function conversationsWithNewFlirt()
    {
        return self::conversationsByIds(
            $this->conversationIds(
                'any',
                'peasant_bot',
                ['flirt']
            ),
            ['user_meta']
        );
    }

    public function countConversations(string $age = 'any', string $lastMessageUserRoles = 'any', array $types = [])
    {
        list($roleQuery, $ageQuery, $typesQuery) = $this->resolveConversationTypeOptions(
            $age,
            $lastMessageUserRoles,
            $types
        );

        $query = 'SELECT count(DISTINCT (c.id)) as total
                    FROM conversations as c
                    JOIN conversation_messages cm ON cm.conversation_id = c.id
                    JOIN conversation_messages lm
                        ON lm.id =
                        (
                            SELECT mi.id
                            FROM conversation_messages mi
                            WHERE mi.conversation_id = c.id
                            ORDER BY mi.created_at DESC
                            LIMIT 1
                        )
                    JOIN users sender ON sender.id = lm.sender_id
                    JOIN users recipient ON recipient.id = lm.recipient_id
                    JOIN role_user sender_role ON sender_role.user_id = lm.sender_id
                    JOIN role_user recipient_role ON recipient_role.user_id = lm.recipient_id
                    ' . $roleQuery .
            $typesQuery .
            $ageQuery .
            ' ORDER BY c.created_at DESC ';

        $results = \DB::select($query);

        return (int)current($results)->total;
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @return Conversation
     */
    public function retrieveConversation(int $userAId, int $userBId, $offset = 0, $limit = 0)
    {
        $conversation = $this->conversation
            ->with(['messages' => function($query) use ($offset, $limit) {
                $query->orderBy('created_at', 'desc');

                if ($offset) {
                    $query->skip($offset);
                }

                if ($limit) {
                    $query->take($limit);
                }
            }])
            ->where('user_a_id', $userAId)
            ->where('user_b_id', $userBId)
            ->orWhere(function ($query) use ($userAId, $userBId) {
                $query->where('user_a_id', $userBId);
                $query->where('user_b_id', $userAId);
            })
            ->first();

        if (!($conversation instanceof Conversation)) {
            return null;
        }

        return $conversation;
    }

    public function getConversationMessagesWithIdHigherThanByParticipantIds(int $userAId, int $userBId, $messageIdHigherThan)
    {
        return $this->conversationMessage
            ->with([
                'sender',
                'recipient',
                'attachment'
            ])
            ->where('sender_id', $userAId)
            ->where('recipient_id', $userBId)
            ->where('id', '>', $messageIdHigherThan)
            ->orWhere(function ($query) use ($userAId, $userBId, $messageIdHigherThan) {
                $query->where('sender_id', $userBId);
                $query->where('recipient_id', $userAId);
                $query->where('id', '>', $messageIdHigherThan);
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getConversationsByUserId(int $userId)
    {
        $conversations = DB::table('conversations')->select(
            'conversations.id as conversation_id',
            'conversations.id as id',
            'conversations.new_activity_for_user_a as conversation_new_activity_for_user_a',
            'conversations.new_activity_for_user_b as conversation_new_activity_for_user_b',
            'conversations.user_a_id as conversation_user_a_id',
            'conversations.user_b_id as conversation_user_b_id',
            'conversations.created_at as conversation_created_at',
            'conversations.updated_at as conversation_updated_at',
            'last_message.id as last_message_id',
            'last_message.created_at as last_message_created_at',
            'last_message.body as last_message_body',
            'last_message.has_attachment as last_message_has_attachment',
            'last_message.type as last_message_type',
            'last_message.recipient_id as last_message_recipient_id',
            'last_message.sender_id as last_message_sender_id',
            'last_message.type as last_message_type',
            'message_attachments.id as attachment_id',
            'message_attachments.filename as attachment_filename',
            'uai.filename as user_a_profile_image_filename',
            'ubi.filename as user_b_profile_image_filename',
            'ua.id as user_a_id',
            'ua.username as user_a_username',
            'ua.username as user_a_username',
            'ub.id as user_b_id',
            'ub.username as user_b_username',
            'uam.gender as user_a_gender',
            'ubm.gender as user_b_gender'
        )
            ->leftJoin('conversation_messages as last_message', function ($join) {
                $join->on('last_message.id', '=', DB::raw('(SELECT  mi.id
                FROM    conversation_messages mi
                WHERE   mi.conversation_id = conversations.id
                ORDER BY mi.created_at DESC
                LIMIT 1)'));
            })
            ->leftJoin('message_attachments', function ($join) {
                $join->on('message_attachments.message_id', '=', 'last_message.id');
            })
            ->leftJoin('users as ua', function ($join) {
                $join->on('ua.id', '=', 'conversations.user_a_id');
            })
            ->leftJoin('users as ub', function ($join) {
                $join->on('ub.id', '=', 'conversations.user_b_id');
            })
            ->leftJoin('user_meta as uam', function ($join) {
                $join->on('uam.user_id', '=', 'ua.id');
            })
            ->leftJoin('user_meta as ubm', function ($join) {
                $join->on('ubm.user_id', '=', 'ub.id');
            })
            ->leftJoin('user_images as uai', function ($join) {
                $join->on('uai.user_id', '=', 'ua.id')
                    ->where('uai.profile', 1);
            })
            ->leftJoin('user_images as ubi', function ($join) {
                $join->on('ubi.user_id', '=', 'ub.id')
                    ->where('ubi.profile', 1);
            })
            ->where('conversations.deleted_at', null)
            ->where(function ($query) use ($userId) {
                $query->where('conversations.user_a_id', $userId)
                    ->orWhere('conversations.user_b_id', $userId);
            })
            ->orderBy('last_message_created_at', 'desc')
            ->get();

//        $conversations = $this->conversation
//            ->with([
//                'messages' => function ($query) {
//                   //$query->offset(0)->limit(2);
//                },
//                'userA', 'userB', 'newActivityParticipant'
//            ])
//            ->where('user_a_id', $userId)
//            ->orWhere(function ($query) use ($userId) {
//                $query->where('user_b_id', $userId);
//            })
//            ->orderBy('updated_at', 'desc')
//            ->get();

        return $conversations;
    }

    public function getHighestConversationId()
    {
        return Conversation::max('id');
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @return Conversation
     */
    public function createOrRetrieveConversation(int $userAId, int $userBId)
    {
        $conversation = $this->conversation
            ->where('user_a_id', $userAId)
            ->where('user_b_id', $userBId)
            ->orWhere(function ($query) use ($userAId, $userBId) {
                $query->where('user_a_id', $userBId);
                $query->where('user_b_id', $userAId);
            })
            ->withTrashed()
            ->first();

        if (!($conversation instanceof Conversation)) {
            $conversation = new Conversation([
                'user_a_id' => $userAId,
                'user_b_id' => $userBId
            ]);

            $conversation->setNewActivityForUserB(true);
        } else {
            if ($conversation->getDeletedAt()) {
                $conversation->setDeletedAt(null);
            }

            if ($conversation->getUserBId() === $userBId) {
                $conversation->setNewActivityForUserB(true);
                $conversation->setNewActivityForUserA(false);
            } else {
                $conversation->setNewActivityForUserA(true);
                $conversation->setNewActivityForUserB(false);
            }
        }

        $conversation->setUpdatedAt(Carbon::now());
        $conversation->save();

        return $conversation;
    }

    /**
     * @param $results
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    private function formatConversations($results, array $options = [])
    {
        $conversations = [];

        foreach ($results as $result) {
            $conversation = [];
            $this->formatConversation($result, $conversation, $options);
            $conversations[] = $conversation;
        }
        return collect($conversations);
    }

    /**
     * @param string $age
     * @param string $lastMessageUserRoles
     * @return array
     * @throws \Exception
     */
    private function resolveConversationTypeOptions(
        string $age,
        string $lastMessageUserRoles,
        array $types,
        bool $startWithAnd = false
    ) {
        $senderRole = null;
        $recipientRole = null;

        $roleQuery = '';
        $typesQuery = '';
        $ageQuery = '';

        $allowedUserRoles = [
            'peasant_bot', // last message-sender is peasant / recipient is bot
            'bot_peasant',
            'peasant_peasant',
            'any'
        ];

        $allowedAges = [
            'only_new', // new means convos with only one participant
            'only_old', // old here means convos with more than one participant
            'any'
        ];

        // check if only allowed options are used
        if (
            in_array($age, $allowedAges) === false ||
            in_array($lastMessageUserRoles, $allowedUserRoles) === false
        ) {
            throw new \Exception;
        }

        // gets set to true if WHERE clause already exists
        $andRequired = false;

        // resolve messages to return based on lastMessage sender/recipient types
        if ($lastMessageUserRoles !== 'any') {
            list($senderRole, $recipientRole) = explode('_', $lastMessageUserRoles);

            $senderRoleId = UserConstants::selectableField(
                'role',
                'common',
                'array_flip'
            )[$senderRole];
            $recipientRoleId = UserConstants::selectableField('role', 'common', 'array_flip')[$recipientRole];

            if ($startWithAnd) {
                $roleQuery = ' AND ';
            } else {
                $roleQuery = ' WHERE ';
            }

            $roleQuery = $roleQuery . ' sender_role.role_id = ' .
                $senderRoleId  .
                ' AND recipient_role.role_id = ' .
                $recipientRoleId . ' ';
            $andRequired = true;
        }

        if (!empty($types)) {
            $typesQuery = " WHERE lm.type IN ('" . implode("','", $types) . "') ";
            if ($andRequired) {
                $typesQuery = str_replace('WHERE', 'AND', $typesQuery);
            }
        }

        // resolve messages to return based on new/old/any
        if ($age !== 'any') {
            $requiredDistinctCount = (explode('_', $age)[1] === 'new') ? 1 : 2;

            $ageQuery = ' GROUP BY conversation_id
                         HAVING COUNT(DISTINCT(cm.sender_id)) = ' . $requiredDistinctCount . ' ';
        }

        return array($roleQuery, $ageQuery, $typesQuery);
    }

    /**
     * @param $result
     * @return array
     * @throws \Exception
     */
    protected function determineParticipantIds($result): array
    {
        if ($result->user_a_id === $result->last_message_sender_id) {
            $senderRoleId = $result->user_a_role_id;
            $recipientRoleId = $result->user_b_role_id;
            return array($senderRoleId, $recipientRoleId);
        } elseif ($result->user_a_id === $result->last_message_recipient_id) {
            $senderRoleId = $result->user_b_role_id;
            $recipientRoleId = $result->user_a_role_id;
            return array($senderRoleId, $recipientRoleId);
        } else {
            throw new \Exception;
        }
    }

    /**
     * @param $result
     * @param $conversation
     */
    private function setConversationFields($result, &$conversation)
    {
        $conversation['id'] = $result->conversation_id;
        $conversation['created_at'] = new Carbon($result->conversation_created_at);
        $conversation['deleted_at'] = $result->conversation_deleted_at ? new Carbon($result->conversation_created_at) : null;
    }

    /**
     * @param $result
     * @param $conversation
     */
    private function setUserAFields($result, &$conversation, $options = [])
    {
        $conversation['user_a']['id'] = $result->user_a_id;
        $conversation['user_a']['username'] = $result->user_a_username;
        $conversation['user_a']['profile_image_url'] = \StorageHelper::profileImageUrlFromId(
            $conversation['user_a']['id'],
            $result->user_a_gender,
            $result->user_a_profile_img
        );

        $conversation['user_a']['role'] = (int) $result->user_a_role_id;

        if (in_array('user_meta', $options)) {
            $conversation['user_a']['meta']['gender'] = (int) $result->user_a_gender;
        }
    }

    /**
     * @param $result
     * @param $conversation
     */
    private function setUserBFields($result, &$conversation, $options = [])
    {
        $conversation['user_b']['id'] = $result->user_b_id;
        $conversation['user_b']['username'] = $result->user_b_username;
        $conversation['user_b']['profile_image_url'] = \StorageHelper::profileImageUrlFromId(
            $conversation['user_b']['id'],
            $result->user_b_gender,
            $result->user_b_profile_img
        );

        $conversation['user_b']['role'] = (int) $result->user_b_role_id;

        if (in_array('user_meta', $options)) {
            $conversation['user_b']['meta']['gender'] = (int) $result->user_b_gender;
        }
    }

    /**
     * @param $result
     * @param $conversation
     * @param $senderRoleId
     * @param $recipientRoleId
     */
    private function setLastMessageFields($result, &$conversation, $senderRoleId, $recipientRoleId)
    {
        $conversation['last_message']['id'] = $result->last_message_id;
        $conversation['last_message']['sender_id'] = $result->last_message_sender_id;
        $conversation['last_message']['sender_role_id'] = $senderRoleId;
        $conversation['last_message']['recipient_id'] = $result->last_message_recipient_id;
        $conversation['last_message']['recipient_role_id'] = $recipientRoleId;
        $conversation['last_message']['body'] = $result->last_message_body;
        $conversation['last_message']['has_attachment'] = $result->last_message_has_attachment;
        $conversation['last_message']['type'] = $result->last_message_type;
        $conversation['last_message']['created_at'] = new Carbon($result->last_message_created_at);
    }

    /**
     * @param $result
     * @param $conversation
     */
    private function formatConversation($result, &$conversation, $options = [])
    {
        list($senderRoleId, $recipientRoleId) = $this->determineParticipantIds($result);

        $this->setConversationFields($result, $conversation);
        $this->setUserAFields($result, $conversation, $options);
        $this->setUserBFields($result, $conversation, $options);
        $this->setLastMessageFields($result, $conversation, $senderRoleId, $recipientRoleId);
    }
}
