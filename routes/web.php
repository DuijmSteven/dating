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

Route::get('password/reset', 'Auth\PasswordController@getEmail')
    ->name('password.reset.get');
Route::post('password/email', 'Auth\PasswordController@postEmail')
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
    Route::get('/{user}', 'frontend\PeasantController@show')
        ->name('users.show');
    Route::put('/(user)', 'frontend\PeasantController@update')
        ->name('users.update');
    Route::delete('/{user}', 'frontend\PeasantController@destroy')
        ->name('users.destroy');
    Route::get('/{user}/edit', 'frontend\PeasantController@edit')
        ->name('users.edit');
});

Route::group([
    'prefix' => 'backend',
    'middleware' => ['admin']
], function () {
    Route::get('/dashboard', 'backend\DashboardController@dashboard')
        ->name('backend.dashboard');

    Route::group([
        'prefix' => 'bots'
    ], function () {
        Route::get('/', 'backend\BotController@index')
            ->name('backend.bots.retrieve');
        Route::get('/create', 'backend\BotController@create')
            ->name('backend.bots.create.get');
        Route::post('/create', 'backend\BotController@store')
            ->name('backend.bots.store');

        Route::get('/edit/{id}', 'backend\BotController@edit')
            ->name('backend.bots.edit.get');
        Route::post('/edit/{id}', 'backend\BotController@update')
            ->name('backend.bots.update');
    });

    Route::group([
        'prefix' => 'users'
    ], function () {
        Route::get('/', 'backend\UserController@index')
            ->name('backend.users.retrieve');

        Route::get('/create', 'backend\UserController@create')
            ->name('backend.users.create.get');
        Route::post('/create', 'backend\UserController@postCreate')
            ->name('backend.users.create.post');

        Route::get('/cities', 'backend\UserController@getCities');
    });
});

