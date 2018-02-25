<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    if (
        ($user->id == \App\Conversation::find($conversationId)->user_a_id)
        ||
        ($user->id == \App\Conversation::find($conversationId)->user_b_id)
    ) {
        return true;
    }
    return false;
});

Broadcast::channel('chat', function ($user) {
    return Auth::check();
});

