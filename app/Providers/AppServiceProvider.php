<?php

namespace App\Providers;

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
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        dd($this->app->environment();
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind('App\Interfaces\PaymentProvider', 'App\Services\PaymentService');
    }
}
