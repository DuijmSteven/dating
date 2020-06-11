<?php

namespace App\Events;

use App\ConversationMessage;
use App\User;
use App\UserMeta;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Events\Login;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LogSuccessfulLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var User */
    public $user;

    public function __construct(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $this->user = $user;
    }

    public function handle(Login $event)
    {
        /** @var User $user */
        $user = User::with('meta')->find($event->user->id);

        $user->meta->logins_count++;
        $user->meta->save();
    }
}
