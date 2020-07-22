<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('user', 'Api\UserController@getCurrentUser')
    ->middleware('auth:api');

Route::group([
    'prefix' => 'users'
], function () {
    Route::get('online/ids', 'Api\UserController@getOnlineUserIds')
        ->name('users.get-online-ids');

    Route::get('{userId}', 'Api\UserController@getUserById')
        ->name('users.get-by-id');

    Route::get('{userId}/credits', 'Api\UserController@getUserCredits')
        ->name('users.get-user-credits');

    Route::get('{userId}/milestones/accepted-welcome-message', 'Api\UserController@acceptedWelcomeMessageMilestone')
        ->name('users.get-user-credits');
});


Route::group([
    'prefix' => 'conversation-messages'
], function () {
    Route::get('{userAId}/{userBId}/{messageIdHigherThan}', 'Api\ConversationController@getConversationMessagesWithIdHigherThanByParticipantIds')
        ->name('conversations-messages.get-messages-with-higher-id');
});

Route::group([
    'prefix' => 'conversations'
], function () {
    Route::get('{conversationId}/get-locked-info', 'Api\ConversationController@getLockedInformation')
        ->name('conversations.get-locked-info');

    Route::get('conversation-partner-ids/{userId}', 'Api\ConversationController@getOpenConversationPartners')
        ->name('conversations.get-user-conversation-partner-ids');

    Route::get('conversation-partner-ids/add/{userId}/{partnerId}/{state}', 'Api\ConversationController@persistConversationPartnerId')
        ->name('conversations.add-user-conversation-partner-ids');

    Route::get('conversation-partner-ids/remove/{userId}/{partnerId}', 'Api\ConversationController@removeConversationPartnerId')
        ->name('conversations.remove-user-conversation-partner-ids');

    Route::get('conversation-manager-state/{userId}/{state}', 'Api\ConversationController@persistConversationManagerState')
        ->name('conversations.persist-conversation-manager-state');

    Route::get('conversation-manager-state/{userId}', 'Api\ConversationController@getConversationManagerState')
        ->name('conversations.get-conversation-manager-state');

    Route::get('/get-highest-id', 'Api\ConversationController@getHighestConversationId')
        ->name('conversations.get-highest-id');

    Route::get('{userAId}/{userBId}/{offset}/{limit}', 'Api\ConversationController@getConversationByParticipantIds')
        ->name('conversations.get-user-ids');
    Route::get('{userId}', 'Api\ConversationController@getConversationsByUserId')
        ->name('conversations.get-user-conversations');

    Route::delete('{conversationId}', 'Api\ConversationController@deleteConversationById')
        ->name('conversations.delete-by-id');

    Route::get('set-conversation-activity-for-user/{userAId}/{userBId}/{userId}/{value}', 'Api\ConversationController@setConversationActivityForUserId')
        ->name('conversations.set-conversation-activity-for-user-id');
});

Route::get('{userId}/chat-translations', 'Api\ConversationController@getChatTranslations')
    ->name('cities.get');

Route::get('cities/{countryCode?}', 'Api\LocationController@getCities')
    ->name('cities.get')
    ->middleware('auth:api');

Route::group([
    'prefix' => 'public-chat'
], function () {
    Route::get('items/{forGender}/{forLookingForGender}/{offset}/{limit}', 'Api\PublicChatController@getPublicChatItems')
        ->name('public-chat.get-items')
        ->middleware(['auth:api']);

    Route::get('items-with-higher-id-than/{messageIdHigherThan}/{forGender}/{forLookingForGender}', 'Api\PublicChatController@getPublicChatItemsWithIdHigherThan')
        ->name('public-chat.get-items-with-higher-id');

//    Route::post('items', 'Api\PublicChatController@post')
//        ->name('public-chat.post-item');
});