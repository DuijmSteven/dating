<?php

namespace App\Events;

use App\ConversationMessage;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public $conversationMessage;

    /**
     * MessageSent constructor.
     * @param User $user
     * @param ConversationMessage $conversationMessage
     */
    public function __construct(User $user, ConversationMessage $conversationMessage)
    {
        $this->user = $user;
        $this->conversationMessage = $conversationMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->conversationMessage->conversation_id);
    }
}
