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
        ($user->id == \App\ConversationMessage::find($conversationId, ['conversation_id'])->first()->sender_id)
        ||
        ($user->id == \App\ConversationMessage::find($conversationId, ['conversation_id'])->first()->recipient_id)
    ) {
        return true;
    }
    return false;
});

/*Broadcast::channel('chat', function ($user) {
    return Auth::check();
});*/

