<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->injectAuthenticatedUser();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function injectAuthenticatedUser()
    {
        view()->composer('frontend.layouts.default.layout', 'App\ViewComposers\BaseComposer');
    }
}
