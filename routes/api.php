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

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group([
    'prefix' => 'users'
], function () {
    Route::get('{userId}', 'Api\UserController@getUserById')
        ->name('users.get-by-id');
});


Route::group([
    'prefix' => 'conversations'
], function () {
    Route::get('{userAId}/{userBId}', 'Api\ConversationController@getConversationByParticipantIds')
        ->name('conversations.get-user-ids');
});

Route::get('cities/{countryCode}', 'Api\LocationController@getCities')
    ->name('cities.get');