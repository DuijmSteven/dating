<?php

namespace App\Managers;

use App\ConversationMessage;
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

    public function createMessage(array $messageData, bool $attachment = false)
    {
        if($attachment) {
            $uploadedImageFilename = $this->storageManager->saveConversationImage($messageData['attachment'], $messageData['conversation_id']);
            $messageData['message'] = '<img src="' . \StorageHelper::conversationImageUrl($messageData['conversation_id'], $uploadedImageFilename) . '" class="img-responsive" />';
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
}
