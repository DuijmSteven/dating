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
    ->name('login.get')
    ->middleware(['guest']);
Route::post('login', 'Auth\LoginController@login')
    ->name('login.post')
    ->middleware(['guest']);
Route::post('logout', 'Auth\LoginController@logout')
    ->name('logout.post');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.reset.get');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.reset.post');

Route::get('contact', 'Frontend\HomeController@showContact')
    ->name('contact.get');

Route::get('/', 'Frontend\HomeController@index')
    ->name('home')->middleware(['auth']);


Route::group([
    'prefix' => 'register',
    'middleware' => ['guest']
], function () {
    Route::get('/', 'Auth\RegisterController@showRegistrationForm')
        ->name('register.get');
});

Route::group([
    'prefix' => 'LP',
    'middleware' => ['guest']
], function () {
    Route::get('/', 'Frontend\LandingPageController@show')
        ->name('landing-page.show');
    Route::post('/', 'Auth\RegisterController@register')
        ->name('register.post');
});


/* User routes */
Route::group([
    'prefix' => 'users',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'Frontend\UserController@index')
        ->name('users.overview');
    Route::get('/search', 'Frontend\UserSearchController@getSearch')
        ->name('users.search.get');
    Route::get('/search-form-get', 'Frontend\UserSearchController@search')
        ->name('users.search.form.get');
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

/* Articles routes */
Route::group([
    'prefix' => 'articles',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'Frontend\ArticleController@index')
        ->name('articles.overview');
    Route::get('/{articleId}', 'Frontend\ArticleController@show')
        ->name('articles.show');
});

/* Conversations routes */
Route::group([
    'prefix' => 'conversations',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'ConversationController@index')
        ->name('conversations.overview');
    Route::get('/{conversationId}/messages', 'ConversationController@conversationMessages')
        ->name('conversation.messages');
    Route::post('/', 'ConversationController@store')
        ->name('conversation.message.store');
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
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

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

        Route::get('{peasantId}/message-as-bot', 'Admin\PeasantController@messagePeasantAsBot')
            ->name('admin.peasants.message-as-bot.get');
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
            ->name('admin.conversations.overview');
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
            ->name('admin.articles.overview');

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
        'prefix' => 'testimonials'
    ], function () {
        Route::get('/', 'Admin\TestimonialController@index')
            ->name('admin.testimonials.overview');

        Route::delete('{testimonialId}', 'Admin\TestimonialController@destroy')
            ->name('admin.testimonials.destroy');

        Route::get('create', 'Admin\TestimonialController@getCreate')
            ->name('admin.testimonials.create');
        Route::post('/', 'Admin\TestimonialController@post')
            ->name('admin.testimonials.post');

        Route::get('{testimonialId}', 'Admin\TestimonialController@getUpdate')
            ->name('admin.testimonials.edit');
        Route::put('{testimonialId}', 'Admin\TestimonialController@update')
            ->name('admin.testimonials.update');
    });

    Route::group([
        'prefix' => 'payments'
    ], function () {
        Route::get('/', 'Admin\PaymentController@index')
            ->name('admin.payments.overview');
    });

    Route::get('layout', 'Admin\ModuleController@showLayout')
        ->name('admin.layout.show');

    Route::group([
        'prefix' => 'modules'
    ], function () {
        Route::get('/', 'Admin\ModuleController@index')
            ->name('admin.modules.overview');

        Route::post('update', 'Admin\ModuleController@updateModules')
            ->name('admin.modules.layout.update');

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

    Route::group([
        'prefix' => 'views'
    ], function () {
        Route::get('/', 'Admin\ViewController@overview')
            ->name('admin.views.overview');
        Route::get('/create', 'Admin\ViewController@showCreate')
            ->name('admin.views.create.show');
        Route::post('/create', 'Admin\ViewController@create')
            ->name('admin.views.create');

        Route::delete('/{viewId}', 'Admin\ViewController@destroy')
            ->name('admin.views.delete');

        Route::get('/{viewId}', 'Admin\ViewController@showUpdate')
            ->name('admin.views.update.show');
        Route::put('/{viewId}', 'Admin\ViewController@update')
            ->name('admin.views.update');

        Route::get('{viewId}/layout', 'Admin\ViewController@showLayout')
            ->name('admin.views.layout.show');
    });

    Route::group([
        'prefix' => 'layout-parts'
    ], function () {
        Route::get('/', 'Admin\LayoutPartController@index')
            ->name('admin.layout-parts.overview');

        Route::delete('{moduleId}', 'Admin\LayoutPartController@destroy')
            ->name('admin.layout-parts.destroy');

        Route::get('create', 'Admin\LayoutPartController@getCreate')
            ->name('admin.layout-parts.create');
        Route::post('', 'Admin\LayoutPartController@post')
            ->name('admin.layout-parts.post');

        Route::get('{moduleId}', 'Admin\LayoutPartController@getUpdate')
            ->name('admin.layout-parts.edit');
        Route::put('{moduleId}', 'Admin\LayoutPartController@update')
            ->name('admin.layout-parts.update');
    });
});

Route::group([
    'prefix' => 'operator-platform',
    'middleware' => ['operator']
], function () {
    Route::get('dashboard', 'Operators\HomeController@showDashboard')
    ->name('operators_platform.dashboard');

    Route::get('send-message-as-bot', 'Operators\ConversationController@showSendMessageAsBot')
        ->name('operators_platform.send_message_as_bot.show');

    Route::group([
        'prefix' => 'conversations'
    ], function () {
        Route::get('{conversationId}', 'Admin\ConversationController@show')
            ->name('operators_platform.conversations.show');
        Route::post('/', 'ConversationController@store')
            ->name('conversations.store');

        Route::post('/', 'Admin\ConversationController@store')
            ->name('admin.conversations.store');

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
    Route::group([
        'prefix' => 'email'
    ], function () {
        Route::get('show-welcome', 'TestController@showWelcomeEmail')
            ->name('test.email.welcome.show');

        Route::get('show-message-received', 'TestController@showMessageReceivedEmail')
            ->name('test.email.message-received.show');

        Route::get('send-test', 'TestController@sendTestEmail')
            ->name('test.email.send-test');
    });
});
