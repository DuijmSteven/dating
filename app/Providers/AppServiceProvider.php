<?php

namespace App\Providers;

use Aws\Ses\Exception\SesException;
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
            $exception = $event->exception;

            if (
                !$exception instanceof SesException // Amazon exception that fails on some weird unknown emails
            ) {
                $logArray['Site'] = [
                    'ID' => config('app.site_id'),
                    'Name' => config('app.name'),
                    'URL' => config('app.url'),
                ];

                $logArray['Job'] = $event->job;

                $traceAsString = $exception->getTraceAsString();
                $traceAsStringParts = str_split($traceAsString, 1900);

                $logArray['Exception Class'] = get_class($exception);
                $logArray['Exception Message'] = $exception->getMessage();

                if (count($traceAsStringParts) > 1) {
                    $loop = 0;
                    foreach ($traceAsStringParts as $part) {
                        $logArray['Stack Trace Part ' . ($loop + 1)] = $part;
                        $loop++;
                    }
                } else {
                    $logArray['Stack Trace'] = $traceAsString;
                }

                Log::channel('slackQueues')
                    ->error(
                        'Site ID: ' . config('app.site_id') . ' - ' . config('app.url'),
                        $logArray
                    );
            }

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
