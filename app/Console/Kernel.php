<?php

namespace App\Console;

use App\Console\Commands\CheckRecentStartedPayments;
use App\Console\Commands\ExportDb;
use App\Console\Commands\SendProfileCompletionEmails;
use App\Console\Commands\SendProfileViewedEmails;
use App\Console\Commands\UpdateCurrentEnvDbAndAws;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SetRandomBotsOnline::class,
        Commands\ExportDb::class,
        Commands\ImportLatestProductionDbExport::class,
        Commands\SendProfileViewedEmails::class,
        Commands\DuplicateProductionS3BucketToCurrentEnvironmentBucket::class,
        Commands\UpdateCurrentEnvDbAndAws::class,
        Commands\SendProfileCompletionEmails::class,
        CheckRecentStartedPayments::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('bots:set-random-online')->everyTenMinutes();

        if (config('app.env') === 'production') {
            $schedule->command(SendProfileViewedEmails::class)->everyMinute();
            $schedule->command(SendProfileCompletionEmails::class)->dailyAt("19:00");
            $schedule->command(ExportDb::class)->dailyAt("05:30");
            $schedule->command(CheckRecentStartedPayments::class)->everyMinute();
        }
        
        if (config('app.env') === 'staging') {
            $schedule->command(UpdateCurrentEnvDbAndAws::class)->dailyAt("05:50");
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
