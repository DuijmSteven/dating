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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/*Route::get('auth/login', 'Auth\AuthController@getLogin')
    ->name('login.get');
Route::post('login', 'Auth\AuthController@postLogin')
    ->name('login.post');
Route::post('logout', 'Auth\AuthController@getLogout')
    ->name('logout.post');

Route::get('register', 'Auth\AuthController@getRegister')
    ->name('register.get');
Route::post('register', 'Auth\AuthController@postRegister')
    ->name('register.post');

Route::get('password/reset', 'Auth\PasswordController@getEmail')
    ->name('password.reset.get');
Route::post('password/email', 'Auth\PasswordController@postEmail')
    ->name('password.reset.post');*/

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
    Route::post('/', 'frontend\UserController@index')
        ->name('users.store');
    Route::get('/create', 'frontend\UserController@create')
        ->name('users.create');
    Route::get('/search', 'frontend\UserSearchController@getSearch')
        ->name('users.search.get');
    Route::post('/search', 'frontend\UserSearchController@postSearch')
        ->name('users.search.post');
    Route::get('/search-results', 'frontend\UserSearchController@getSearchResults')
        ->name('users.search.results.get');
    Route::get('/online', 'frontend\UserController@online')
        ->name('users.online');
    Route::get('/{user}', 'frontend\UserController@show')
        ->name('users.show');
    Route::put('/(user)', 'frontend\UserController@update')
        ->name('users.update');
    Route::delete('/{user}', 'frontend\UserController@destroy')
        ->name('users.destroy');
    Route::get('/{user}/edit', 'frontend\UserController@edit')
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

