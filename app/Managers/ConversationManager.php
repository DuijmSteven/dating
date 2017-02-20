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
        $newConversationIds = $this->newConversationIds();

        return self::conversationsByIds($newConversationIds, true);
    }

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function unrepliedPeasantBotConversations()
    {
        $unrepliedPeasantBotConversations = $this->nonNewConversations();

        $unrepliedPeasantBotConversations = $this->
            filterConversationsByUserType($unrepliedPeasantBotConversations);

        return $unrepliedPeasantBotConversations;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function newConversationIds()
    {
        $newConversationIds = \DB::table('conversations')->distinct()->select('id')
            ->join(
                \DB::raw('(SELECT conversation_messages.conversation_id,
                                     conversation_messages.sender_id
                              FROM conversation_messages
                              GROUP BY conversation_messages.conversation_id
                              HAVING COUNT(DISTINCT (conversation_messages.sender_id)) = 1)
                              AS messages'),
                function ($join) {
                    $join->on('conversations.id', '=', 'messages.conversation_id');
                }
            )
            ->pluck('id')->toArray();

        return $newConversationIds;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function nonNewConversations()
    {
        $nonNewConversationsIds = \DB::table('conversations')->distinct()->select('id')
            ->join(
                \DB::raw('(SELECT conversation_messages.conversation_id,
                                     conversation_messages.sender_id
                              FROM conversation_messages
                              GROUP BY conversation_messages.conversation_id
                              HAVING COUNT(DISTINCT (conversation_messages.sender_id)) = 2)
                              AS messages'),
                function ($join) {
                    $join->on('conversations.id', '=', 'messages.conversation_id');
                }
            )
            ->pluck('id')->toArray();

        return self::conversationsByIds($nonNewConversationsIds);
    }

    public function newFlirts()
    {
        $allConversations = Conversation::with(['messages'])->get();
        return self::filterConversationsByUserAndLastMessageType($allConversations);
    }

    private function filterConversationsByUserAndLastMessageType(\Illuminate\Support\Collection $conversations)
    {
        $results = [];
        foreach ($conversations as $conversation) {
            $lastMessage = $conversation->messages->first();

            $lastUserType = $lastMessage->sender->roles[0]->id;
            $otherUserType = $lastMessage->recipient->roles[0]->id;

            $lastMessageType = $conversation->messages->first()->type;

            if ($lastUserType == UserConstants::selectableField('role', 'common', 'array_flip')['peasant'] &&
                $otherUserType == UserConstants::selectableField('role', 'common', 'array_flip')['bot'] &&
                $lastMessageType === 'flirt'
            ) {
                $results[] = $conversation;
            }
        }

        return $results;
    }

    /**
     * @param \Illuminate\Support\Collection $conversations
     * @return array
     */
    private function filterConversationsByUserType(array $conversations)
    {
        $results = [];
        foreach ($conversations as $conversation) {
            $lastUserType = $conversation->messages->first()->sender->roles[0]->id;
            $otherUserType = $conversation->messages->first()->recipient->roles[0]->id;

            if ($lastUserType == UserConstants::selectableField('role', 'common', 'array_flip')['peasant'] &&
                $otherUserType == UserConstants::selectableField('role', 'common', 'array_flip')['bot']
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

    public function conversationsByIds(array $conversationIds, $excludeRealConversations = true)
    {
        $excludeRealConversationsQuery = $excludeRealConversations ?
            ' AND ((user_a_role.role_id = 2 AND user_b_role.role_id = 3) OR (user_a_role.role_id = 3 AND user_b_role.role_id = 2)) ' :
            '';

        $results = \DB::select('SELECT  c.id as conversation_id,
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
                                        $excludeRealConversationsQuery .
                                        'ORDER BY c.created_at DESC
                                    ');

        $conversations = [];

        foreach ($results as $result) {
            $conversation = [];

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
            $conversation['last_message']['recipient_id'] = $result->last_message_recipient_id;
            $conversation['last_message']['body'] = $result->last_message_body;
            $conversation['last_message']['has_attachment'] = $result->last_message_has_attachment;
            $conversation['last_message']['type'] = $result->last_message_type;
            $conversation['last_message']['created_at'] = $result->last_message_created_at;

            $conversation['id'] = $result->conversation_id;
            $conversation['id'] = $result->conversation_id;
            $conversation['id'] = $result->conversation_id;

            $conversations[$result->conversation_id] = $conversation;
        }

        \Log::info($conversations);
        return $conversations;
    }
}
