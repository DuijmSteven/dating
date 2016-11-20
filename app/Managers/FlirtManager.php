<?php

namespace App\Managers;

use App\Flirt;

class FlirtManager
{
    /**
     * @param int $senderId
     * @param int $recipientId
     * @return bool
     */
    public function send(int $senderId, int $recipientId)
    {
        $flirt = new Flirt;

        $flirt->sender_id = $senderId;
        $flirt->recipient_id = $recipientId;

        return $flirt->save();
    }
}
