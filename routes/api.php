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

    Route::get('conversation-partner-ids/add/{userId}/{partnerId}', 'Api\ConversationController@persistConversationPartnerId')
        ->name('conversations.add-user-conversation-partner-ids');

    Route::get('conversation-partner-ids/remove/{userId}/{partnerId}', 'Api\ConversationController@removeConversationPartnerId')
        ->name('conversations.remove-user-conversation-partner-ids');

    Route::get('{userAId}/{userBId}', 'Api\ConversationController@getConversationByParticipantIds')
        ->name('conversations.get-user-ids');
    Route::get('{userId}', 'Api\ConversationController@getConversationsByUserId')
        ->name('conversations.get-user-conversations');
});

Route::get('cities/{countryCode}', 'Api\LocationController@getCities')
    ->name('cities.get');