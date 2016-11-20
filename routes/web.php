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

Route::post('login', 'Auth\LoginController@login')
    ->name('login.post');
Route::post('logout', 'Auth\LoginController@logout')
    ->name('logout.post');

Route::get('login', 'Auth\LoginController@showLoginForm')
    ->name('login.get');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')
    ->name('register.get');
Route::post('register', 'Auth\RegisterController@register')
    ->name('register.post');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.reset.get');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.reset.post');

Route::get('/', 'frontend\HomeController@index')
    ->name('root');
Route::get('/home', 'frontend\HomeController@index')
    ->name('home');

/* User routes */
Route::group([
    'prefix' => 'users',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'frontend\UserController@index')
        ->name('users.retrieve');
    Route::post('/', 'frontend\PeasantController@index')
        ->name('users.store');
    Route::get('/create', 'frontend\PeasantController@create')
        ->name('users.create');
    Route::get('/search', 'frontend\UserSearchController@getSearch')
        ->name('users.search.get');
    Route::post('/search', 'frontend\UserSearchController@postSearch')
        ->name('users.search.post');
    Route::get('/search-results', 'frontend\UserSearchController@getSearchResults')
        ->name('users.search.results.get');
    Route::get('/online', 'frontend\UserController@online')
        ->name('users.online');
    Route::get('/{userId}', 'frontend\UserController@show')
        ->name('users.show');
    Route::put('/(user)', 'frontend\PeasantController@update')
        ->name('users.update');
    Route::delete('/{user}', 'frontend\PeasantController@destroy')
        ->name('users.destroy');
    Route::get('/{user}/edit', 'frontend\PeasantController@edit')
        ->name('users.edit');
});

Route::delete('images/{imageId}/delete', 'UserImagesController@destroy')
    ->name('images.destroy');
Route::get('images/{imageId}/set-profile', 'UserImagesController@setProfileImage')
    ->name('images.set_profile');
Route::get('images/{imageId}/toggle-visibility', 'UserImagesController@toggleImageVisibility')
    ->name('images.toggle_visibility');

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
        Route::get('/cities', 'Backend\UserController@getCities');
    });
});

