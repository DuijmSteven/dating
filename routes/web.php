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

use Illuminate\Support\Facades\Route;

Route::post('login', 'Auth\LoginController@login')
    ->name('login.post')
    ->middleware(['guest']);

Route::post('logout', 'Auth\LoginController@logout')
    ->name('logout.post');

//SEO Redirects
Route::permanentRedirect('/articles/1', '/articles/4-tips-om-een-snelle-online-seksdate-te-regelen-als-man');
Route::permanentRedirect('/articles/2', '/articles/sexplaatsen-top-12');

Route::group([
    'middleware' => ['anonymous_domain', 'guest']
], function()
{
    Route::get('operators/login', 'Admin\OperatorController@showLogin')
        ->name('operators.show-login');
});

Route::group([
    'middleware' => ['not_anonymous_domain']
], function()
{
    Route::get('/login', 'Frontend\LandingPageController@showLogin')
        ->middleware('guest')
        ->name('landing-page.show-login');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
        ->name('password.reset.get');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
        ->name('password.email');

    Route::post('password/reset', 'Auth\ResetPasswordController@reset')
        ->name('password.reset.post');

    Route::get('password/reset-final', 'Auth\ResetPasswordController@showResetForm')
        ->name('password.reset.final.get');

    Route::get('contact', 'Frontend\ContactController@showContact')
        ->name('contact.get');
    Route::post('contact', 'Frontend\ContactController@postContact')
        ->name('contact.post');

    Route::get('/home', 'Frontend\DashboardController@index')
        ->name('home')
        ->middleware(['auth', 'not_editor', 'not_operator']);

    Route::get('direct-login/{user}/{routeName?}/{routeParamName?}/{routeParam?}', 'Frontend\DashboardController@directLogin')
        ->name('direct-login')
        ->middleware('signed');

    Route::get('/lps/{id}/{lpType?}', 'Frontend\AdsLandingPagesController@showLP')
        ->name('ads-lp.show')
        ->middleware('guest');

    Route::get('/', 'Frontend\LandingPageController@showRegister')
        ->middleware('guest')
        ->name('landing-page.show-register');

    Route::post('/', 'Auth\RegisterController@register')
        ->middleware('guest')
        ->name('register.post');

    Route::get('deactivated', 'Frontend\UserController@showDeactivated')
        ->name('users.deactivated.get');

    Route::post('redirect-back', 'Frontend\UserController@redirectBack')
        ->name('users.redirect-back');

    Route::group([
        'prefix' => 'public-chat-items'
    ], function () {
        Route::post('/', 'Frontend\PublicChatController@post')
            ->name('public-chat-items.post');
    });
});

/* User routes */
Route::group([
    'prefix' => 'users',
    'middleware' => ['auth', 'not_editor', 'not_operator', 'not_anonymous_domain']
], function () {

    Route::group([
        'prefix' => 'users',
        'middleware' => ['auth']
    ], function () {
        Route::put('{userId}/images/update', 'UserImageController@update')
            ->name('users.images.update');
    });

    Route::get('/', 'Frontend\UserController@index')
        ->name('users.overview');
    Route::get('search', 'Frontend\UserSearchController@getSearch')
        ->name('users.search.get');
    Route::post('search-form-get', 'Frontend\UserSearchController@search')
        ->name('users.search.form.get');
    Route::get('search-results', 'Frontend\UserSearchController@getSearchResults')
        ->name('users.search.results.get');
    Route::get('online', 'Frontend\UserController@online')
        ->name('users.online');
    Route::get('{username}', 'Frontend\UserController@show')
        ->name('users.show');
    Route::get('{userId}/set-profile-image/{imageId}', 'UserImageController@setProfileImage')
        ->name('users.set-profile-image');
    Route::get('set-locale/{locale}', 'Frontend\UserController@setLocale')
        ->name('users.set-locale');

    Route::put('{userId}/edit', 'Frontend\UserController@update')
        ->name('users.update');

    Route::get('{username}/edit-profile', 'Frontend\UserController@showEditProfile')
        ->name('users.edit-profile.get')
        ->middleware('current_user');

    Route::get('{userId}/deactivate', 'Frontend\UserController@deactivate')
        ->name('users.deactivate.get')
        ->middleware('current_user');

    Route::post('current/accept-profile-completion-message', 'Frontend\UserController@acceptProfileCompletionMessage')
        ->name('users.current.accept-profile-completion-message');

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
    'middleware' => ['not_anonymous_domain']
], function () {
    Route::get('/', 'Frontend\ArticleController@index')
        ->name('articles.overview');
    Route::get('/{slug}', 'Frontend\ArticleController@show')
        ->name('articles.show');
});

/* Unsubscribe routes */
Route::group([
    'prefix' => 'unsubscribe',
    'middleware' => ['not_anonymous_domain']
], function () {
    Route::get('/{user}', 'Frontend\EmailUnsubscribeController@index')
        ->name('unsubscribe')->middleware('signed');
    Route::post('/{user}', 'Frontend\EmailUnsubscribeController@unsubscribe')
        ->name('unsubscribe.post')->middleware('signed');
});

/* Conversations routes */
Route::group([
    'prefix' => 'conversations',
    'middleware' => ['auth', 'not_editor', 'not_operator', 'not_anonymous_domain']
], function () {
    Route::get('/', 'ConversationController@index')
        ->name('conversations.overview');
    Route::get('/{conversationId}/messages', 'ConversationController@conversationMessages')
        ->name('conversation.messages');
    Route::post('/', 'ConversationController@store')
        ->name('conversation.message.store');
});

Route::group([
    'prefix' => 'images',
    'middleware' => ['auth', 'not_editor', 'not_operator', 'not_anonymous_domain']
], function () {
    Route::delete('{imageId}/delete', 'UserImageController@destroy')
        ->name('images.destroy');
    Route::get('{imageId}/toggle-visibility', 'UserImageController@toggleImageVisibility')
        ->name('images.toggle_visibility');
});

Route::group([
    'prefix' => 'flirts',
    'middleware' => ['auth', 'not_editor', 'not_operator', 'not_anonymous_domain']
], function () {
    Route::get('{senderId}/{recipientId}', 'Frontend\FlirtController@send');
});

Route::get('cities/{countryCode}', 'Admin\UserController@getCities')
    ->name('cities.retrieve');

Route::group([
    'prefix' => 'payments',
    'middleware' => ['auth', 'not_editor', 'not_operator', 'not_anonymous_domain']
], function () {
    Route::post('/', 'Frontend\PaymentController@postPayment')
        ->name('payments.post');
    Route::get('thank-you', 'Frontend\PaymentController@checkPayment')
        ->middleware(['has_non_completed_payment'])
        ->name('payments.check');
});

Route::get('privacy', 'Frontend\MiscController@showPrivacy')
    ->name('privacy.show');

Route::get('tac', 'Frontend\MiscController@showTac')
    ->name('tac.show');

Route::get('faq', 'Frontend\MiscController@showFaq')
    ->name('faq.show');

Route::group([
    'prefix' => 'credits',
    'middleware' => ['auth', 'not_editor', 'not_operator', 'not_anonymous_domain']
], function () {
    Route::get('/', 'Frontend\CreditsController@show')
        ->name('credits.show');
    Route::post('/', 'Frontend\PaymentController@makePayment')
        ->name('credits.store');
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['admin', 'not_anonymous_domain']
], function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::get('dashboard', 'Admin\DashboardController@dashboard')
        ->name('admin.dashboard');

    Route::get('statistics/most-useful', 'Admin\StatisticsController@mostUseful')
        ->name('admin.statistics.most-useful');

    Route::get('statistics/general', 'Admin\StatisticsController@index')
        ->name('admin.statistics.general');

    Route::get('statistics/google-ads', 'Admin\StatisticsController@googleAds')
        ->name('admin.statistics.google-ads');

    Route::get('statistics/xpartners', 'Admin\StatisticsController@xpartners')
        ->name('admin.statistics.xpartners');

    Route::get('statistics/operators', 'Admin\StatisticsController@operators')
        ->name('admin.statistics.operators');

    Route::get('statistics/user-acquisition', 'Admin\StatisticsController@userAcquisition')
        ->name('admin.statistics.user-acquisition');

    Route::get('statistics/google-ads/keywords', 'Admin\StatisticsController@googleAdsKeywords')
        ->name('admin.statistics.google-ads.keywords');

    Route::post('statistics/google-ads/keywords/search', 'Admin\StatisticsController@searchGoogleAdsKeywords')
        ->name('admin.statistics.google-ads.search');

    Route::get('statistics/best-bots', 'Admin\StatisticsController@bestBots')
        ->name('admin.statistics.best-bots');

    Route::post('users/search', 'Admin\UserSearchController@search')
        ->name('admin.users.search.post');
    Route::get('users/search-results', 'Admin\UserSearchController@getSearchResults')
        ->name('admin.users.search.results.get');

    Route::group([
        'prefix' => 'bots'
    ], function () {
        Route::get('/', 'Admin\BotController@index')
            ->name('admin.bots.retrieve');

        Route::delete('{id}', 'Admin\BotController@destroy')
            ->name('admin.bots.destroy');

        Route::get('{id}/set-too-slutty-for-ads/{tooSlutty}', 'Admin\BotController@setTooSluttyForAds')
            ->name('admin.bots.set-too-slutty-for-ads');

        Route::get('on-map', 'Admin\BotController@showOnMap')
            ->name('admin.bots.map.show');

        Route::get('create', 'Admin\BotController@create')
            ->name('admin.bots.create.get');

        Route::get('edit/{botId}', 'Admin\BotController@edit')
            ->name('admin.bots.edit.get');

        Route::get('online', 'Admin\BotController@showOnline')
            ->name('admin.bots.online.show');

        Route::get('{botId}/message-with-bot/{onlyOnlinePeasants}', 'Admin\BotController@messagePeasantWithBot')
            ->name('admin.bots.message-with-bot.get');
    });

    Route::group([
        'prefix' => 'operators'
    ], function () {
        Route::get('/', 'Admin\OperatorController@index')
            ->name('admin.operators.overview');

        Route::delete('{id}', 'Admin\OperatorController@destroy')
            ->name('admin.operators.destroy');

        Route::get('{operatorId}/messages', 'Admin\OperatorController@messages')
            ->name('admin.operators.messages.overview');

        Route::get('{operatorId}/messages', 'Admin\MessageController@ofOperatorId')
            ->name('admin.operators.messages.overview');

        Route::get('edit/{operatorId}', 'Admin\OperatorController@edit')
            ->name('admin.operators.edit.get');

        Route::put('edit/{userId}', 'Admin\PeasantController@update')
            ->name('admin.operators.update');

        Route::get('/online', 'Admin\OperatorController@showOnline')
            ->name('admin.operators.online.show');

        Route::get('create', 'Admin\OperatorController@create')
            ->name('admin.operators.create.get');

        Route::post('create', 'Admin\OperatorController@store')
            ->name('admin.operators.store');
    });

    Route::group([
        'prefix' => 'editors'
    ], function () {
        Route::get('/', 'Admin\EditorController@index')
            ->name('admin.editors.overview');

        Route::delete('{id}', 'Admin\EditorController@destroy')
            ->name('admin.editors.destroy');

        Route::get('/{editorId}/created-bots', 'Admin\EditorController@createdBots')
            ->name('admin.editors.created-bots.overview');

        Route::get('edit/{editorId}', 'Admin\EditorController@edit')
            ->name('admin.editors.edit.get');

        Route::put('edit/{userId}', 'Admin\PeasantController@update')
            ->name('admin.editors.update');

        Route::get('/online', 'Admin\EditorController@showOnline')
            ->name('admin.editors.online.show');
    });

    Route::group([
        'prefix' => 'invoices'
    ], function () {
        Route::get('/', 'Admin\InvoiceController@index')
            ->name('admin.invoices.overview');

        Route::get('create', 'Admin\InvoiceController@getCreate')
            ->name('admin.invoices.create.get');

        Route::post('post', 'Admin\InvoiceController@fromParameters')
            ->name('admin.invoices.post');

//        Route::get('from-parameters/{userId}/{fromDate}/{untilDate}', 'Admin\InvoiceController@fromParameters')
//            ->name('admin.invoices.from-parameters');
    });

    Route::group([
        'prefix' => 'peasants'
    ], function () {
        Route::get('/', 'Admin\PeasantController@index')
            ->name('admin.peasants.retrieve');

        Route::get('/from-{affiliate}', 'Admin\PeasantController@fromAffiliate')
            ->name('admin.peasants.from-affiliate');

        Route::get('{peasantId}/validate-xpartners-lead', 'Admin\PeasantController@validateXpartnersLead')
            ->name('admin.peasants.validate-xpartners-lead');

        Route::delete('{id}', 'Admin\PeasantController@destroy')
            ->name('admin.peasants.destroy');

        Route::get('/on-map', 'Admin\PeasantController@showOnMap')
            ->name('admin.peasants.map.show');

        Route::get('/online', 'Admin\PeasantController@showOnline')
            ->name('admin.peasants.online.show');

        Route::get('/conversions', 'Admin\PeasantController@conversions')
            ->name('admin.peasants.conversions.show');

        Route::get('/created-until-date/{date}', 'Admin\PeasantController@createdUntilDate')
            ->name('admin.peasants.created-until-date.show');

        Route::get('create', 'Admin\PeasantController@create')
            ->name('admin.peasants.create.get');
        Route::post('create', 'Admin\PeasantController@store')
            ->name('admin.peasants.store');

        Route::get('edit/{peasantId}', 'Admin\PeasantController@edit')
            ->name('admin.peasants.edit.get');
        Route::put('edit/{userId}', 'Admin\PeasantController@update')
            ->name('admin.peasants.update');

        Route::get('{peasantId}/message-as-bot/{onlyOnlineBots}', 'Admin\PeasantController@messagePeasantAsBot')
            ->name('admin.peasants.message-as-bot.get');

        Route::get('/deactivations', 'Admin\PeasantController@deactivations')
            ->name('admin.peasants.deactivations.overview');

        Route::get('/fingerprints', 'Admin\PeasantController@fingerprints')
            ->name('admin.peasants.fingerprints.overview');

        Route::get('/with-creditpack', 'Admin\PeasantController@withCreditpack')
            ->name('admin.peasants.with-creditpack.overview');
    });

    Route::group([
        'prefix' => 'users'
    ], function () {
        Route::delete('{userId}', 'Admin\UserController@destroy')
            ->name('admin.users.destroy');
    });

    Route::group([
        'prefix' => 'mass-messages'
    ], function () {
        Route::get('new', 'Admin\MassMessageController@new')
            ->name('admin.mass-messages.new');

        Route::post('send', 'Admin\MassMessageController@send')
            ->name('admin.mass-messages.send');
    });

    Route::group([
        'prefix' => 'conversations'
    ], function () {
        Route::get('/', 'Admin\ConversationController@index')
            ->name('admin.conversations.overview');
        Route::get('users/{peasantId}', 'Admin\ConversationController@ofPeasantId')
            ->name('admin.conversations.peasant.get');
        Route::get('users/{botId}', 'Admin\ConversationController@ofBotId')
            ->name('admin.conversations.bot.get');

        Route::get('operators/{operatorId}', 'Admin\ConversationController@withOperatorId')
            ->name('admin.conversations.with-operator');

        Route::delete('{conversationId}', 'Admin\ConversationController@destroy')
            ->name('admin.conversations.destroy');

        Route::get('/{conversationId}/unlock', 'Admin\ConversationController@unlock')
            ->name('admin.conversations.unlock');

        Route::get('/{conversationId}/set-unreplyable', 'Admin\ConversationController@setUnreplyable')
            ->name('admin.conversations.set-unreplyable');
    });

    Route::group([
        'prefix' => 'messages'
    ], function () {
        Route::get('/', 'Admin\MessageController@index')
            ->name('admin.messages.overview');
        Route::get('peasants/{peasantId}', 'Admin\MessageController@ofPeasantId')
            ->name('admin.messages.peasant');
        Route::get('bots/{botId}', 'Admin\MessageController@ofBotId')
            ->name('admin.messages.bot');
        Route::get('operators/{operatorId}', 'Admin\MessageController@ofOperatorId')
            ->name('admin.messages.operator');
        Route::delete('{messageId}', 'Admin\MessageController@destroy')
            ->name('admin.messages.destroy');
    });

    Route::group([
        'prefix' => 'public-chat-items'
    ], function () {
        Route::get('/', 'Admin\PublicChatItemController@index')
            ->name('admin.public-chat-items.overview');
        Route::get('peasants/{peasantId}', 'Admin\PublicChatItemController@ofPeasantId')
            ->name('admin.public-chat-items.peasant');
        Route::get('bots/{botId}', 'Admin\PublicChatItemController@ofBotId')
            ->name('admin.public-chat-items.bot');
        Route::get('operators/{operatorId}', 'Admin\PublicChatItemController@ofOperatorId')
            ->name('admin.public-chat-items.operator');
        Route::delete('{chatItemId}', 'Admin\PublicChatItemController@destroy')
            ->name('admin.public-chat-items.destroy');

        Route::get('send-as-bot', 'Admin\PublicChatItemController@showSendAsBot')
            ->name('admin.public-chat-items.send-as-bot.show');

        Route::post('send-as-bot', 'Admin\PublicChatItemController@sendAsBot')
            ->name('admin.public-chat-items.send-as-bot.post');
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
        'prefix' => 'expenses'
    ], function () {
        Route::get('/', 'Admin\ExpenseController@index')
            ->name('admin.expenses.overview');

        Route::delete('{expenseId}', 'Admin\ExpenseController@destroy')
            ->name('admin.expenses.destroy');

        Route::get('create', 'Admin\ExpenseController@getCreate')
            ->name('admin.expenses.create');
        Route::post('/', 'Admin\ExpenseController@post')
            ->name('admin.expenses.post');

        Route::get('{expenseId}', 'Admin\ExpenseController@getUpdate')
            ->name('admin.expenses.edit');
        Route::put('{expenseId}', 'Admin\ExpenseController@update')
            ->name('admin.expenses.update');
    });

    Route::group([
        'prefix' => 'bot-messages'
    ], function () {
        Route::get('/', 'Admin\BotMessageController@index')
            ->name('admin.bot-messages.overview');

        Route::get('bots/{botId}', 'Admin\BotMessageController@ofBotId')
            ->name('admin.bot-messages.bot.get');

        Route::delete('{botMessageId}', 'Admin\BotMessageController@destroy')
            ->name('admin.bot-messages.destroy');

        Route::get('create', 'Admin\BotMessageController@getCreate')
            ->name('admin.bot-messages.create');
        Route::post('/', 'Admin\BotMessageController@post')
            ->name('admin.bot-messages.post');

        Route::get('{botMessageId}', 'Admin\BotMessageController@getUpdate')
            ->name('admin.bot-messages.edit');
        Route::put('{botMessageId}', 'Admin\BotMessageController@update')
            ->name('admin.bot-messages.update');
    });

    Route::group([
        'prefix' => 'faqs'
    ], function () {
        Route::get('/', 'Admin\FaqController@index')
            ->name('admin.faqs.overview');

        Route::delete('{faqId}', 'Admin\FaqController@destroy')
            ->name('admin.faqs.destroy');

        Route::get('create', 'Admin\FaqController@getCreate')
            ->name('admin.faqs.create');
        Route::post('/', 'Admin\FaqController@post')
            ->name('admin.faqs.post');

        Route::get('{faqId}', 'Admin\FaqController@getUpdate')
            ->name('admin.faqs.edit');
        Route::put('{faqId}', 'Admin\FaqController@update')
            ->name('admin.faqs.update');
    });

    Route::group([
        'prefix' => 'tacs'
    ], function () {
        Route::get('/', 'Admin\TacController@index')
            ->name('admin.tacs.overview');

        Route::delete('{tacId}', 'Admin\TacController@destroy')
            ->name('admin.tacs.destroy');

        Route::get('create', 'Admin\TacController@getCreate')
            ->name('admin.tacs.create');
        Route::post('/', 'Admin\TacController@post')
            ->name('admin.tacs.post');

        Route::get('{tacId}', 'Admin\TacController@getUpdate')
            ->name('admin.tacs.edit');
        Route::put('{tacId}', 'Admin\TacController@update')
            ->name('admin.tacs.update');
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
        'prefix' => 'payments',
    ], function () {
        Route::get('/', 'Admin\PaymentController@index')
            ->name('admin.payments.overview');

        Route::get('/completed', 'Admin\PaymentController@completed')
            ->name('admin.payments.completed');
        Route::get('{peasantId}', 'Admin\PaymentController@ofPeasantId')
            ->name('admin.payments.peasant.overview');
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
    'prefix' => 'editor',
    'middleware' => ['editor', 'anonymous_domain']
], function () {
    Route::group([
        'prefix' => 'bots'
    ], function () {
        Route::get('created', 'Admin\EditorController@createdBots')
            ->name('editors.bots.created.overview');
        Route::get('create', 'Admin\BotController@create')
            ->name('editors.bots.create.get');
        Route::get('on-map', 'Admin\BotController@showOnMap')
            ->name('editors.bots.map.show');
        Route::get('{botId}', 'Admin\BotController@edit')
            ->name('editors.bots.edit.get');

    });
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['editor', 'anonymous_domain']
], function () {
    Route::group([
        'prefix' => 'bots'
    ], function () {
        Route::post('create', 'Admin\BotController@store')
            ->name('admin.bots.store');
        Route::put('edit/{id}', 'Admin\BotController@update')
            ->name('admin.bots.update');
    });
});

Route::group([
    'prefix' => 'operator-platform',
    'middleware' => ['operator', 'anonymous_domain']
], function () {
    Route::get('dashboard', 'Operators\DashboardController@showDashboard')
    ->name('operator-platform.dashboard');

    Route::group([
        'prefix' => 'conversations'
    ], function () {
        Route::get('exists/{userAId}/{userBId}', 'Admin\ConversationController@checkIfConversationExists')
            ->name('admin.conversations.check-if-exists');

        Route::get('new/{userAId}/{userBId}', 'Admin\ConversationController@showNew')
            ->name('operator-platform.new-conversation.show');

        Route::get('{conversationId}/{messagesAfterDate?}/{messagesBeforeDate?}', 'Admin\ConversationController@show')
                ->name('operator-platform.conversations.show');

        Route::post('/store', 'ConversationController@store')
            ->name('conversations.store');

        Route::post('/admin/store', 'Admin\ConversationController@store')
            ->name('admin.conversations.store');

        Route::post('/admin/add-invisible-image', 'Admin\ConversationController@addInvisibleImageToConversation')
            ->name('admin.conversations.add-invisible-image');

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

    Route::get('example-invoice', 'TestController@exampleInvoice')
        ->name('test.invoices.show-example');

    Route::group([
        'prefix' => 'email'
    ], function () {
        Route::get('show-welcome', 'TestController@showWelcomeEmail')
            ->name('test.email.welcome.show');

        Route::get('show-please-come-back', 'TestController@showPleaseComeBackEmail')
            ->name('test.email.please-come-back.show');

        Route::get('show-promo', 'TestController@showDatingsitelijstPromoEmail')
            ->name('test.email.datingsitelist-promo.show');

        Route::get('show-credits-bought', 'TestController@showCreditsBoughtEmail')
            ->name('test.email.credits-bought.show');

        Route::get('show-deactivated', 'TestController@showDeactivatedEmail')
            ->name('test.email.deactivated.show');

        Route::get('show-message-received', 'TestController@showMessageReceivedEmail')
            ->name('test.email.message-received.show');

        Route::get('show-profile-viewed', 'TestController@showProfileViewedEmail')
            ->name('test.email.profile-viewed.show');

        Route::get('show-profile-completion', 'TestController@showProfileCompletionEmail')
            ->name('test.email.profile-completion.show');

        Route::get('send-test', 'TestController@sendTestEmail')
            ->name('test.email.send-test');
    });
});
