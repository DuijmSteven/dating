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
        $newConversations = $this->newConversations();

        $newPeasantBotConversations = $this->filterConversationsByUserType($newConversations);

        return $newPeasantBotConversations;
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
    public function newConversations()
    {
        $newConversationsIds = \DB::table('conversations')->distinct()->select('id')
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

        return self::conversationsByIds($newConversationsIds);
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
                              AS messages')
                , function ($join) {
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
    private function filterConversationsByUserType(\Illuminate\Support\Collection $conversations)
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

    public function conversationsByIds(array $conversationIds)
    {
        $conversations = \DB::select('SELECT conversations.id, conversations.user_a_id, conversations.user_b_id, conversations.created_at,
                                        notes.user_id, notes.category, notes.title, notes.body
                                        FROM conversations
                                        JOIN conversation_notes notes ON notes.conversation_id = conversations.id
                                        ');

        \Log::info($conversations);
        die();
    }
}
