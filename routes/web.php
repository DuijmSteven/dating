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
    'prefix' => 'payments'
], function () {
    Route::post('/', 'Frontend\PaymentController@postPayment')
    ->name('payments.post');
    Route::get('initiate', 'Frontend\PaymentController@initiatePayment')
    ->name('payments.initiate');
});

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
        Route::delete('{userId}', 'Backend\UserController@destroy')
            ->name('backend.users.destroy');
    });

    Route::group([
        'prefix' => 'conversations'
    ], function () {
        Route::get('/', 'Backend\ConversationController@index')
            ->name('backend.conversations.index');
        Route::delete('/{conversationId}', 'Backend\ConversationController@destroy')
            ->name('backend.conversations.destroy');
    });

    Route::group([
        'prefix' => 'notes'
    ], function () {
        Route::delete('/{noteId}', 'Backend\ConversationNoteController@destroyNote')
            ->name('backend.conversations.notes.destroy');
    });

    Route::group([
        'prefix' => 'articles'
    ], function () {
        Route::get('/', 'Backend\ArticleController@index')
            ->name('backend.articles.index');

        Route::delete('/{articleId}', 'Backend\ArticleController@destroy')
            ->name('backend.articles.destroy');

        Route::get('/create', 'Backend\ArticleController@getCreate')
            ->name('backend.articles.create');
        Route::post('/', 'Backend\ArticleController@post')
            ->name('backend.articles.post');

        Route::get('/{articleId}', 'Backend\ArticleController@getUpdate')
            ->name('backend.articles.edit');
        Route::put('/{articleId}', 'Backend\ArticleController@update')
            ->name('backend.articles.update');
    });

    Route::group([
        'prefix' => 'payments'
    ], function () {
        Route::get('/', 'Backend\PaymentController@index')
            ->name('backend.payments.index');
    });

    Route::group([
        'prefix' => 'modules'
    ], function () {
        Route::get('/', 'Backend\ModuleController@index')
            ->name('backend.modules.index');

        Route::delete('/{moduleId}', 'Backend\ModuleController@destroy')
            ->name('backend.modules.destroy');

        Route::get('/create', 'Backend\ModuleController@getCreate')
            ->name('backend.modules.create');
        Route::post('/', 'Backend\ModuleController@post')
            ->name('backend.modules.post');

        Route::get('/{moduleId}', 'Backend\ModuleController@getUpdate')
            ->name('backend.modules.edit');
        Route::put('/{moduleId}', 'Backend\ModuleController@update')
            ->name('backend.modules.update');
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
        Route::post('/', 'ConversationController@store')
            ->name('conversations.store');

        Route::group([
            'prefix' => 'notes'
        ], function () {
            Route::post('/', 'Backend\ConversationNoteController@postNote')
                ->name('backend.conversations.notes.store');
        });
    });
});

Route::group([
    'prefix' => 'test'
], function () {
});
