<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', 'Auth\LoginController@showLoginForm')
    ->name('login.get');
Route::post('login', 'Auth\LoginController@login')
    ->name('login.post');
Route::post('logout', 'Auth\LoginController@logout')
    ->name('logout.post');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')
    ->name('register.get');
Route::post('register', 'Auth\RegisterController@register')
    ->name('register.post');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.reset.get');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.reset.post');

Route::get('contact', 'Frontend\HomeController@showContact')
    ->name('contact.get');


Route::get('/', 'Frontend\HomeController@index')
    ->name('root')->middleware(['auth']);
Route::get('home', 'Frontend\HomeController@index')
    ->name('home')->middleware(['auth']);

/* User routes */
Route::group([
    'prefix' => 'users',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'Frontend\UserController@index')
        ->name('users.retrieve');
    Route::get('/search', 'Frontend\UserSearchController@getSearch')
        ->name('users.search.get');
    Route::post('/search', 'Frontend\UserSearchController@postSearch')
        ->name('users.search.post');
    Route::get('/search-results', 'Frontend\UserSearchController@getSearchResults')
        ->name('users.search.results.get');
    Route::get('/online', 'Frontend\UserController@online')
        ->name('users.online');
    Route::get('/{userId}', 'Frontend\UserController@show')
        ->name('users.show');
    Route::get('{userId}/set-profile-image/{imageId}', 'UserImageController@setProfileImage')
        ->name('users.set_profile_image');

    Route::group([
        'prefix' => 'favorites'
    ], function () {
        Route::post('{userId}/{favoriteId}', 'FavoriteController@storeUserFavorite');
        Route::delete('{userId}/{favoriteId}', 'FavoriteController@deleteUserFavorite');
        Route::get('{userId}', 'FavoriteController@retrieveUserFavorites');
    });
});

Route::group([
    'prefix' => 'images'
], function () {
    Route::delete('{imageId}/delete', 'UserImageController@destroy')
        ->name('images.destroy');
    Route::get('{imageId}/toggle-visibility', 'UserImageController@toggleImageVisibility')
        ->name('images.toggle_visibility');
});

Route::group([
    'prefix' => 'flirts'
], function () {
    Route::get('/{senderId}/{recipientId}', 'Frontend\FlirtController@send');
});

Route::get('cities/{countryCode}', 'Backend\UserController@getCities')
    ->name('cities.retrieve');

Route::group([
    'prefix' => 'backend',
    'middleware' => ['admin']
], function () {
    Route::get('/dashboard', 'Backend\DashboardController@dashboard')
        ->name('backend.dashboard');

    Route::group([
        'prefix' => 'bots'
    ], function () {
        Route::get('/', 'Backend\BotController@index')
            ->name('backend.bots.retrieve');
        Route::get('/create', 'Backend\BotController@create')
            ->name('backend.bots.create.get');
        Route::post('/create', 'Backend\BotController@store')
            ->name('backend.bots.store');

        Route::get('/edit/{id}', 'Backend\BotController@edit')
            ->name('backend.bots.edit.get');
        Route::put('/edit/{id}', 'Backend\BotController@update')
            ->name('backend.bots.update');
    });

    Route::group([
        'prefix' => 'peasants'
    ], function () {
        Route::get('/', 'Backend\PeasantController@index')
            ->name('backend.peasants.retrieve');
        Route::get('/create', 'Backend\PeasantController@create')
            ->name('backend.peasants.create.get');
        Route::post('/create', 'Backend\PeasantController@store')
            ->name('backend.peasants.store');

        Route::get('/edit/{id}', 'Backend\PeasantController@edit')
            ->name('backend.peasants.edit.get');
        Route::put('/edit/{id}', 'Backend\PeasantController@update')
            ->name('backend.peasants.update');
    });

    Route::group([
        'prefix' => 'users'
    ], function () {
        Route::delete('{userId}/delete', 'Backend\UserController@destroy')
            ->name('backend.users.destroy');
    });
});

Route::group([
    'prefix' => 'operator-platform',
    'middleware' => ['operator']
], function () {
    Route::get('dashboard', 'Operators\HomeController@showDashboard')
    ->name('operators_platform.dashboard');

    Route::group([
        'prefix' => 'conversations'
    ], function () {
        Route::get('{conversationId}', 'Backend\ConversationController@show')
            ->name('operators_platform.conversations.show');
        Route::post('/create', 'ConversationController@store')
            ->name('conversations.store');
    });
});

Route::group([
    'prefix' => 'test'
], function () {
});
