<?php

namespace App\Providers;

use Carbon\Carbon;
use Http\Adapter\Guzzle7\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
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

        Queue::failing(function (JobFailed $event) {
            $logArray['Site'] = [
                'ID' => config('app.site_id'),
                'Name' => config('app.name'),
                'URL' => config('app.url'),
            ];

            $logArray['Job'] = $event->job;
            $logArray['Exception Message'] = $event->exception->getMessage();

            Log::channel('slackQueues')
                ->error(
                    'Site ID: ' . config('app.site_id') . ' - ' . config('app.url'),
                    $logArray
                );
        });

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
