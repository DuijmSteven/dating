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
    Route::get('{senderId}/{recipientId}', 'Frontend\FlirtController@send');
});

Route::get('cities/{countryCode}', 'Admin\UserController@getCities')
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
    'prefix' => 'admin',
    'middleware' => ['admin']
], function () {
    Route::get('dashboard', 'Admin\DashboardController@dashboard')
        ->name('admin.dashboard');

    Route::group([
        'prefix' => 'bots'
    ], function () {
        Route::get('/', 'Admin\BotController@index')
            ->name('admin.bots.retrieve');
        Route::get('/create', 'Admin\BotController@create')
            ->name('admin.bots.create.get');
        Route::post('/create', 'Admin\BotController@store')
            ->name('admin.bots.store');

        Route::get('/edit/{id}', 'Admin\BotController@edit')
            ->name('admin.bots.edit.get');
        Route::put('/edit/{id}', 'Admin\BotController@update')
            ->name('admin.bots.update');
    });

    Route::group([
        'prefix' => 'peasants'
    ], function () {
        Route::get('/', 'Admin\PeasantController@index')
            ->name('admin.peasants.retrieve');
        Route::get('create', 'Admin\PeasantController@create')
            ->name('admin.peasants.create.get');
        Route::post('create', 'Admin\PeasantController@store')
            ->name('admin.peasants.store');

        Route::get('edit/{id}', 'Admin\PeasantController@edit')
            ->name('admin.peasants.edit.get');
        Route::put('edit/{id}', 'Admin\PeasantController@update')
            ->name('admin.peasants.update');
    });

    Route::group([
        'prefix' => 'users'
    ], function () {
        Route::delete('{userId}', 'Admin\UserController@destroy')
            ->name('admin.users.destroy');
    });

    Route::group([
        'prefix' => 'conversations'
    ], function () {
        Route::get('/', 'Admin\ConversationController@index')
            ->name('admin.conversations.index');
        Route::delete('{conversationId}', 'Admin\ConversationController@destroy')
            ->name('admin.conversations.destroy');
    });

    Route::group([
        'prefix' => 'notes'
    ], function () {
        Route::delete('{noteId}', 'Admin\ConversationNoteController@destroyNote')
            ->name('admin.conversations.notes.destroy');
    });

    Route::group([
        'prefix' => 'articles'
    ], function () {
        Route::get('/', 'Admin\ArticleController@index')
            ->name('admin.articles.index');

        Route::delete('{articleId}', 'Admin\ArticleController@destroy')
            ->name('admin.articles.destroy');

        Route::get('create', 'Admin\ArticleController@getCreate')
            ->name('admin.articles.create');
        Route::post('/', 'Admin\ArticleController@post')
            ->name('admin.articles.post');

        Route::get('{articleId}', 'Admin\ArticleController@getUpdate')
            ->name('admin.articles.edit');
        Route::put('{articleId}', 'Admin\ArticleController@update')
            ->name('admin.articles.update');
    });

    Route::group([
        'prefix' => 'payments'
    ], function () {
        Route::get('/', 'Admin\PaymentController@index')
            ->name('admin.payments.index');
    });

    Route::group([
        'prefix' => 'modules'
    ], function () {
        Route::get('/', 'Admin\ModuleController@index')
            ->name('admin.modules.index');
        Route::get('/left-sidebar', 'Admin\ModuleController@showLeftSidebar')
            ->name('admin.modules.left-sidebar.show');
        Route::get('/right-sidebar', 'Admin\ModuleController@showRightSidebar')
            ->name('admin.modules.right-sidebar.show');

        Route::delete('{moduleId}', 'Admin\ModuleController@destroy')
            ->name('admin.modules.destroy');

        Route::get('create', 'Admin\ModuleController@getCreate')
            ->name('admin.modules.create');
        Route::post('', 'Admin\ModuleController@post')
            ->name('admin.modules.post');

        Route::get('{moduleId}', 'Admin\ModuleController@getUpdate')
            ->name('admin.modules.edit');
        Route::put('{moduleId}', 'Admin\ModuleController@update')
            ->name('admin.modules.update');
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
        Route::get('{conversationId}', 'Admin\ConversationController@show')
            ->name('operators_platform.conversations.show');
        Route::post('/', 'ConversationController@store')
            ->name('conversations.store');

        Route::group([
            'prefix' => 'notes'
        ], function () {
            Route::post('/', 'Admin\ConversationNoteController@postNote')
                ->name('admin.conversations.notes.store');
        });
    });
});

Route::group([
    'prefix' => 'test'
], function () {
});
