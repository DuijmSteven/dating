<?php


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

use Illuminate\Support\Facades\Route;

Route::get('user', 'Api\UserController@getCurrentUser')
    ->middleware('auth:sanctum');

Route::get('user-with-relations', 'Api\UserController@getCurrentUserWithRelations')
    ->middleware('auth:sanctum');


Route::group([
    'prefix' => 'users'
], function () {
    Route::post('token', 'Auth\LoginController@sanctumToken');

    Route::get('online/ids', 'Api\UserController@getOnlineUserIds')
        ->name('users.get-online-ids');
});

Route::group([
    'prefix' => 'users',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('search/{page}', 'Api\UserSearchController@getPaginatedSearchResults');

    Route::get('active-operators', 'Api\UserController@getActiveOperators')
        ->name('users.get-active-operators');

    Route::get('paginated/{roleId}/{page}', 'Api\UserController@getUsersPaginated')
        ->name('users.get-paginated');

    Route::get('{userId}/credits', 'Api\UserController@getUserCredits')
        ->name('users.get-user-credits');

    Route::get('{userId}/{roleId?}', 'Api\UserController@getUserById')
        ->name('users.get-by-id');

    Route::get('{userId}/milestones/accepted-welcome-message', 'Api\UserController@acceptedWelcomeMessageMilestone')
        ->name('users.milestones.award.accepted-welcome-message');

    Route::delete('{id}/destroy', 'Api\UserController@destroy');

    Route::group([
        'prefix' => '{userId}',
    ], function () {
        Route::group([
            'prefix' => 'images',
            'middleware' => ['can_manipulate_user']
        ], function () {
            Route::delete('{imageId}/destroy', 'Api\UserImageController@destroy');
            Route::get('{imageId}/set-profile', 'Api\UserImageController@setProfile');
            Route::get('{imageId}/toggle-visibility', 'Api\UserImageController@toggleVisibility');
        });
    });


});

Route::group([
    'prefix' => 'bots',
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('create', 'Api\BotController@create');
    Route::put('{botId}/update', 'Api\BotController@update');
});

Route::group([
    'prefix' => 'peasants',
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('create', 'Api\PeasantController@create');
    Route::put('{peasantId}/update', 'Api\PeasantController@update');
});

Route::group([
    'prefix' => 'operators',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('{username}/invoice-related-stats/{fromDate}/{untilDate}', 'Api\OperatorController@getInvoiceRelatedData');
});

Route::group([
    'prefix' => 'conversation-messages'
], function () {
    Route::get('{userAId}/{userBId}/{messageIdHigherThan}', 'Api\ConversationController@getConversationMessagesWithIdHigherThanByParticipantIds')
        ->name('conversations-messages.get-messages-with-higher-id');
});

Route::get('admin-dashboard-data', 'Api\AdminDashboardController@getAdminDashboardData')
    ->middleware('auth:sanctum', 'api_admin');

Route::get('financial-data', 'Api\FinancialController@getFinancialData')
    ->middleware('auth:sanctum', 'api_admin');

Route::get('site-dashboard-data', 'Api\AdminDashboardController@getSiteDashboardData')
    ->middleware('auth:sanctum', 'api_admin');

Route::group([
    'prefix' => 'conversations',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('operator-platform-dashboard-data', 'Api\ConversationController@getOperatorPlatformDashboardData')
        ->middleware('api_operator');

    Route::get('{conversationId}/lock/{limit?}', 'Api\ConversationController@lockAndGetData')
        ->middleware('api_operator');

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

    Route::get('get-highest-id', 'Api\ConversationController@getHighestConversationId')
        ->name('conversations.get-highest-id');

    Route::get('{userAId}/{userBId}/{offset}/{limit}', 'Api\ConversationController@getConversationByParticipantIds')
        ->name('conversations.get-user-ids');

    Route::get('{userId}', 'Api\ConversationController@getConversationsByUserId')
        ->name('conversations.get-user-conversations');

    Route::post('store', 'Api\ConversationController@store');

    Route::post('add-invisible-image', 'Api\ConversationController@addInvisibleImage')
        ->name('conversations.add-invisible-image');

    Route::get('notes/categories', 'Api\ConversationNoteController@getCategories');
    Route::post('notes', 'Api\ConversationNoteController@create');
    Route::delete('notes/{noteId}', 'Api\ConversationNoteController@destroy');

    Route::delete('{conversationId}', 'Api\ConversationController@deleteConversationById')
        ->name('conversations.delete-by-id');

    Route::get('set-conversation-activity-for-user/{userAId}/{userBId}/{userId}/{value}', 'Api\ConversationController@setConversationActivityForUserId')
        ->name('conversations.set-conversation-activity-for-user-id');


});

Route::get('{userId}/chat-translations', 'Api\ConversationController@getChatTranslations')
    ->name('chat-translations.get');

Route::get('cities/{countryCode?}', 'Api\LocationController@getCities')
    ->name('cities.get');

Route::group([
    'prefix' => 'public-chat',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('items/{forGender}/{forLookingForGender}/{offset}/{limit}', 'Api\PublicChatController@getPublicChatItems')
        ->name('public-chat.get-items');

    Route::get('items-with-higher-id-than/{messageIdHigherThan}/{forGender}/{forLookingForGender}', 'Api\PublicChatController@getPublicChatItemsWithIdHigherThan')
        ->name('public-chat.get-items-with-higher-id');
});

Route::group([
    'prefix' => 'public-chat-items',
    'middleware' => ['auth:sanctum']
], function () {

    Route::get('paginated/{page}', 'Api\PublicChatItemController@getPaginated')
        ->name('public-chat-items.get-paginated');
});