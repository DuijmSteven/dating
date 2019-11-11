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
    Route::get('{userId}', 'Api\UserController@getUserById')
        ->name('users.get-by-id');
});


Route::group([
    'prefix' => 'conversations'
], function () {
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

Route::get('cities/{countryCode}', 'Api\LocationController@getCities')
    ->name('cities.get');