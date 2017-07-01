<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * Class FacadeServiceProvider
 * @package App\Providers
 */
class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('meta_constants', function () {
            return new \App\Helpers\ApplicationConstants\MetaConstants;
        });

        $this->app->bind('pagination_constants', function () {
            return new \App\Helpers\ApplicationConstants\PaginationConstants;
        });

        $this->app->bind('user_constants', function () {
            return new \App\Helpers\ApplicationConstants\UserConstants;
        });

        $this->app->bind('payments_helper', function () {
            return new \App\Helpers\PaymentsHelper;
        });

        $this->app->bind('storage_helper', function () {
            return new \App\Helpers\StorageHelper;
        });
    }
}
