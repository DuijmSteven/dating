<?php

namespace App\Providers;

use Carbon\Carbon;
use Http\Adapter\Guzzle7\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') == 'local') {
            \DB::enableQueryLog();
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        Carbon::setLocale('nl');

        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {;
        if ($this->app->environment() == 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind('App\Interfaces\PaymentProvider', 'App\Services\PaymentService');

        $this->app->bind('mailgun.client', function() {
            return Client::createWithConfig([]);
        });
    }
}
