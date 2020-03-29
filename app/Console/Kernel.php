<?php

namespace App\Console;

use App\Console\Commands\CheckRecentStartedPayments;
use App\Console\Commands\ExportDb;
use App\Console\Commands\SendProfileCompletionEmails;
use App\Console\Commands\SendProfileViewedEmails;
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
        $this->scheduleRandomOnlineBots($schedule);

        if (config('app.env') === 'production') {
            $schedule->command(SendProfileViewedEmails::class)->everyMinute();
            $schedule->command(SendProfileCompletionEmails::class)->dailyAt("19:00");
            $schedule->command(ExportDb::class)->dailyAt("04:30");
            $schedule->command(CheckRecentStartedPayments::class)->everyMinute();
        }
        
        if (config('app.env') === 'staging') {
            $schedule->command(UpdateCurrentEnvDbAndAws::class)->dailyAt("16:10");
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

    /**
     * @param Schedule $schedule
     */
    protected function scheduleRandomOnlineBots(Schedule $schedule): void
    {
        $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

        $numberOfBotsToHaveOnline = rand(10, 20);

        if ($timeNow->hour > 6 && $timeNow->hour < 18) {
            $numberOfBotsToHaveOnline = rand(20, 35);
        } elseif ($timeNow->hour >= 18 && $timeNow->hour <= 22) {
            $numberOfBotsToHaveOnline = rand(35, 45);
        } elseif ($timeNow->hour > 22 && $timeNow->hour <= 23) {
            $numberOfBotsToHaveOnline = rand(25, 35);
        } elseif ($timeNow->hour >= 0 && $timeNow->hour <= 1) {
            $numberOfBotsToHaveOnline = rand(15, 25);
        } elseif ($timeNow->hour > 2 && $timeNow->hour <= 6) {
            $numberOfBotsToHaveOnline = rand(0, 6);
        }

        $schedule->command('bots:set-random-online ' . $numberOfBotsToHaveOnline)->everyTenMinutes();
    }
}
