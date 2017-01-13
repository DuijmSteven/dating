<?php

namespace App\Managers;

use App\Conversation;
use App\ConversationMessage;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Helpers\ccampbell\ChromePhp\ChromePhp;
use Illuminate\Support\Facades\DB;

class ConversationManager
{

    /** @var StorageManager */
    private $storageManager;

    /**
     * ConversationManager constructor.
     * @param StorageManager $storageManager
     */
    public function __construct(StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    /**
     * @param array $messageData
     * @throws \Exception
     */
    public function createMessage(array $messageData)
    {

        if (isset($messageData['attachment'])) {
            $uploadedImageFilename = $this->storageManager->saveConversationImage(
                $messageData['attachment'],
                $messageData['conversation_id']
            );
            $messageData['message'] = '<img src="' .
                \StorageHelper::conversationImageUrl(
                    $messageData['conversation_id'],
                    $uploadedImageFilename
                ) . '" class="img-responsive" />';
        }
        DB::beginTransaction();
        try {
            $messageInstance = new ConversationMessage([
                "conversation_id" => $messageData['conversation_id'],
                "sender_id" => $messageData['sender_id'],
                "recipient_id" => $messageData['recipient_id'],
                "body" => $messageData['message']
            ]);

            $messageInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }

    /**
     * @return array
     */
    public function newPeasantBotConversations()
    {
        $newConversations = $this->newConversations();

        $newPeasantBotConversations = $this->filterConversationsByParticipantType($newConversations);

        return $newPeasantBotConversations;
    }

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function unrepliedPeasantBotConversations()
    {
        $unrepliedPeasantBotConversations = $this->nonNewConversations();

        $unrepliedPeasantBotConversations = $this->filterConversationsByParticipantType($unrepliedPeasantBotConversations);

        return $unrepliedPeasantBotConversations;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function newConversations()
    {
        $newConversationsIds = \DB::table('conversations')->distinct()->select('id')
            ->join(\DB::raw('(SELECT conversation_messages.conversation_id,
                                     conversation_messages.sender_id
                              FROM conversation_messages
                              GROUP BY conversation_messages.conversation_id
                              HAVING COUNT(DISTINCT (conversation_messages.sender_id)) = 1)
                              AS messages'), function ($join) {
                $join->on('conversations.id', '=', 'messages.conversation_id');
            })
            ->pluck('id');

        return Conversation::with(['userA', 'userB', 'messages'])
            ->whereIn('id', $newConversationsIds)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function nonNewConversations()
    {
        $nonNewConversationsIds = \DB::table('conversations')->distinct()->select('id')
            ->join(\DB::raw('(SELECT conversation_messages.conversation_id,
                                     conversation_messages.sender_id
                              FROM conversation_messages
                              GROUP BY conversation_messages.conversation_id
                              HAVING COUNT(DISTINCT (conversation_messages.sender_id)) = 2)
                              AS messages'), function ($join) {
                $join->on('conversations.id', '=', 'messages.conversation_id');
})
            ->pluck('id');

        return Conversation::with(['userA', 'userB', 'messages'])
            ->whereIn('id', $nonNewConversationsIds)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param \Illuminate\Support\Collection $conversations
     * @return array
     */
    private function filterConversationsByParticipantType(\Illuminate\Support\Collection $conversations)
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
}
