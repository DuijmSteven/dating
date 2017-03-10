<?php

namespace App\Managers;

use App\Conversation;
use App\ConversationMessage;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Helpers\ccampbell\ChromePhp\ChromePhp;
use App\MessageAttachment;
use Illuminate\Support\Facades\DB;

class ConversationManager
{
    /** @var Conversation */
    private $conversation;

    /** @var StorageManager */
    private $storageManager;

    /**
     * ConversationManager constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(Conversation $conversation, StorageManager $storageManager)
    {
        $this->conversation = $conversation;
        $this->storageManager = $storageManager;
    }

    /**
     * @param array $messageData
     * @throws \Exception
     */
    public function createMessage(array $messageData)
    {
        $hasAttachment = isset($messageData['attachment']);

        if ($hasAttachment) {
            $uploadedImageFilename = $this->storageManager->saveConversationImage(
                $messageData['attachment'],
                $messageData['conversation_id']
            );
        }

        DB::beginTransaction();

        try {
            $conversation = $this->createOrRetrieveConversation(
                $messageData['sender_id'],
                $messageData['recipient_id']
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $messageInstance = new ConversationMessage([
                'conversation_id' => $messageData['conversation_id'],
                'type' => 'generic',
                'sender_id' => $messageData['sender_id'],
                'recipient_id' => $messageData['recipient_id'],
                'body' => $messageData['message'],
                'has_attachment' => $hasAttachment,
            ]);

            $messageInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        if ($hasAttachment) {
            try {
                $messageAttachment = new MessageAttachment([
                    'conversation_id' => $messageData['conversation_id'],
                    'message_id' => $messageInstance->id,
                    'filename' => $uploadedImageFilename,
                ]);

                $messageAttachment->save();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        }
        DB::commit();
    }

    /**
     * @return array
     */
    public function newPeasantBotConversations()
    {
        return self::filterConversationsByUserAndLastMessageType(
            self::conversationsByIds(
                $this->conversationIds('only_new', 'peasant_bot'),
                true
            )
        );
    }

    /**
     * @return array
     */
    public function unrepliedPeasantBotConversations()
    {
        return self::filterConversationsByUserAndLastMessageType(
            self::conversationsByIds(
                $this->conversationIds('only_old', 'peasant_bot'),
                true
            )
        );
    }

    /**
     * @param string $age
     * @param string $lastMessageUserRoles
     * @return array
     * @throws \Exception
     */
    public function conversationIds(string $age = 'any', string $lastMessageUserRoles = 'any')
    {
        list($roleQuery, $ageQuery) = $this->resolveConversationTypeOptions(
            $age,
            $lastMessageUserRoles
        );


        $query = 'SELECT DISTINCT(cm.conversation_id) as conversation_id,
                         cm.created_at,
                         lm.id as lm_id,
                         lm.body as lm_body,
                         lm.type as lm_type,
                         sender.id as lm_sender_id,
                         recipient.id as lm_recipient_id,
                         sender_role.role_id as lm_sender_role_id,
                         recipient_role.role_id as lm_recipient_role_id
                  FROM conversation_messages as cm
                  JOIN conversation_messages lm
                    ON  lm.id =
                        (
                            SELECT  mi.id
                            FROM    conversation_messages mi
                            WHERE   mi.conversation_id = cm.conversation_id
                            ORDER BY mi.created_at DESC
                            LIMIT 1
                        )
                  JOIN users sender ON sender.id = lm.sender_id
                  JOIN users recipient ON recipient.id = lm.recipient_id
                  JOIN role_user sender_role ON sender_role.user_id = lm.sender_id
                  JOIN role_user recipient_role ON recipient_role.user_id = lm.recipient_id
                  ' . $roleQuery . '
                  ORDER BY cm.created_at DESC
        ';

        $results = \DB::select($query);

        \Log::info($results);
        die();
        return $newConversationIds;
    }

    /**
     * @return array
     */
    public function conversationsWithNewFlirt()
    {
        $conversationsWithFlirtIds = \DB::table('conversation_messages')
            ->distinct()
            ->select('conversation_messages.conversation_id')
            ->join('role_user as sender_role', 'sender_role.user_id', 'conversation_messages.sender_id')
            ->join('role_user as recipient_role', 'recipient_role.user_id', 'conversation_messages.recipient_id')
            ->where(function ($query) {
                $query->where('sender_role.role_id', 2);
                $query->where('recipient_role.role_id', 3);
            })->pluck('conversation_messages.conversation_id')->toArray();

        return self::filterConversationsByUserAndLastMessageType(
            self::conversationsByIds(
                $conversationsWithFlirtIds,
                true
            ),
            ['flirt']
        );
    }

    /**
     * @param array $conversations
     * @param array $lastMessageTypes
     * @return array
     */
    private function filterConversationsByUserAndLastMessageType(array $conversations, array $lastMessageTypes = [])
    {
        $results = [];

        foreach ($conversations as $conversation) {
            $senderRoleId = $conversation['last_message']['sender_role_id'];
            $recipientRoleId = $conversation['last_message']['recipient_role_id'];

            if (
                empty($lastMessageTypes) ||
                in_array($conversation['last_message']['type'], $lastMessageTypes) !== false
            ) {
                $results[] = $conversation;
            }
        }

        return $results;
    }

    /**
     * @param int $userAId
     * @param int $userBId
     * @return Conversation
     */
    private function createOrRetrieveConversation(int $userAId, int $userBId)
    {
        $conversation = $this->conversation
            ->where('user_a_id', $userAId)
            ->where('user_b_id', $userBId)
            ->orWhere(function ($query) use ($userAId, $userBId) {
                $query->where('user_a_id', $userBId);
                $query->where('user_b_id', $userAId);
            })
            ->first();

        if (!($conversation instanceof Conversation)) {
            $conversation = new Conversation([
                'user_a_id' => $userAId,
                'user_b_id' => $userBId
            ]);

            $conversation->save();
        }

        return $conversation;
    }

    public function conversationsByIds(array $conversationIds, $excludeRealConversations = true, int $limit = 0, int $offset = 0)
    {
        $conversations = [];

        if (empty($conversationIds)) {
            return $conversations;
        }

        $excludeRealConversationsQuery = $excludeRealConversations ?
            ' AND ((user_a_role.role_id = 2 AND user_b_role.role_id = 3) OR (user_a_role.role_id = 3 AND user_b_role.role_id = 2)) ' :
            '';

        $query = 'SELECT  c.id as conversation_id,
                                              m.id as last_message_id, m.created_at as last_message_created_at, m.body as last_message_body, m.has_attachment as last_message_has_attachment, m.type as last_message_type,
                                              m.sender_id as last_message_sender_id, m.recipient_id as last_message_recipient_id,
                                              user_a.id as user_a_id, user_b.id as user_b_id, user_a.username as user_a_username, user_b.username as user_b_username,
                                              user_a_images.filename as user_a_img, user_b_images.filename as user_b_img, user_a_role.role_id as user_a_role_id,
                                              user_b_role.role_id as user_b_role_id
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
                                        WHERE c.id IN (' . implode(',', $conversationIds) . ')' .
            $excludeRealConversationsQuery . '
                                        ORDER BY c.created_at DESC
                                    ';


        if ($limit) {
            $query .= ' LIMIT = ' . $limit;
        }

        if ($offset) {
            $query .= ' LIMIT = ' . $offset;
        }


        $results = \DB::select($query);
        $conversations = $this->formatConversations($results);

        return $conversations;
    }

    /**
     * @param $results
     * @return array
     * @throws \Exception
     */
    protected function formatConversations($results)
    {
        $conversations = [];

        foreach ($results as $result) {
            $conversation = [];

            if ($result->user_a_id === $result->last_message_sender_id) {
                $senderRoleId = $result->user_a_role_id;
                $recipientRoleId = $result->user_b_role_id;
            } elseif ($result->user_a_id === $result->last_message_recipient_id) {
                $senderRoleId = $result->user_b_role_id;
                $recipientRoleId = $result->user_a_role_id;
            } else {
                throw new \Exception;
            }

            $conversation['user_a']['id'] = $result->user_a_id;
            $conversation['user_a']['username'] = $result->user_a_username;
            $conversation['user_a']['profile_image_url'] = $result->user_a_img;
            $conversation['user_a']['role_id'] = $result->user_a_role_id;

            $conversation['user_b']['id'] = $result->user_b_id;
            $conversation['user_b']['username'] = $result->user_b_username;
            $conversation['user_b']['profile_image_url'] = $result->user_b_img;
            $conversation['user_b']['role_id'] = $result->user_b_role_id;

            $conversation['last_message']['id'] = $result->last_message_id;
            $conversation['last_message']['sender_id'] = $result->last_message_sender_id;
            $conversation['last_message']['sender_role_id'] = $senderRoleId;
            $conversation['last_message']['recipient_id'] = $result->last_message_recipient_id;
            $conversation['last_message']['recipient_role_id'] = $recipientRoleId;
            $conversation['last_message']['body'] = $result->last_message_body;
            $conversation['last_message']['has_attachment'] = $result->last_message_has_attachment;
            $conversation['last_message']['type'] = $result->last_message_type;
            $conversation['last_message']['created_at'] = $result->last_message_created_at;

            $conversation['id'] = $result->conversation_id;

            $conversations[$result->conversation_id] = $conversation;
        }
        return $conversations;
    }

    /**
     * @param string $age
     * @param string $lastMessageUserRoles
     * @return array
     * @throws \Exception
     */
    private function resolveConversationTypeOptions(string $age, string $lastMessageUserRoles)
    {
        $anyRole = false;
        $senderRole = null;
        $recipientRole = null;

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

        // resolve messages to return based on lastMessage sender/recipient types
        if ($lastMessageUserRoles === 'any') {
            $roleQuery = '';
        } else {
            list($senderRole, $recipientRole) = explode('_', $lastMessageUserRoles);

            $senderRoleId = UserConstants::selectableField('role', 'common', 'array_flip')[$senderRole];
            $recipientRoleId = UserConstants::selectableField('role', 'common', 'array_flip')[$recipientRole];

            $roleQuery = ' WHERE sender_role.role_id = ' . $senderRoleId  . ' AND recipient_role.role_id = '.  $recipientRoleId . ' ';
        }

        // resolve messages to return based on new/old/any
        if ($age === 'any') {
            $ageQuery = '';
        } else {
            $requiredDistinctCount = (explode('_', $age)[1] === 'new') ? 1 : 2;

            $ageQuery = ' GROUP BY conversation_messages.conversation_id
                         HAVING COUNT(DISTINCT (conversation_messages.sender_id)) = ' . $requiredDistinctCount . ') ';
        }

        return array($roleQuery, $ageQuery);
    }
}
