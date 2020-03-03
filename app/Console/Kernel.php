<?php

namespace App\Console;

use App\Console\Commands\ExportDb;
use App\Console\Commands\UpdateCurrentEnvDbAndAws;
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
        Commands\SendProfileCompletionEmails::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

        $numberOfBotsToHaveOnline = rand(10,20);

        if ($timeNow->hour > 6 && $timeNow->hour < 18) {
            $numberOfBotsToHaveOnline = rand(20, 35);
        } elseif (($timeNow->hour >= 18 && $timeNow->hour <= 23) || ($timeNow->hour >= 0 && $timeNow->hour <= 1)) {
            $numberOfBotsToHaveOnline = rand(35, 45);
        } elseif ($timeNow->hour > 2 && $timeNow->hour <= 6) {
            $numberOfBotsToHaveOnline = rand(0, 6);
        }

        $schedule->command('bots:set-random-online ' . $numberOfBotsToHaveOnline)->everyTenMinutes();

        if (config('app.env') === 'production') {
            $schedule->command(ExportDb::class)->dailyAt("04:30");
            $schedule->command('users:emails:send-profile-viewed')->everyMinute();
            $schedule->command('users:emails:send-profile-completion')->dailyAt("17:00");
        }
        
        if (config('app.env') === 'staging') {
            $schedule->command(UpdateCurrentEnvDbAndAws::class)->dailyAt("05:30");
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
