<?php

namespace App\Console;

use App\Console\Commands\ExportDb;
use App\Console\Commands\SetRandomBotsOnline;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Kim\Activity\Activity;

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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ExportDb::class)->daily();

        $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

        $numberOfBotsToHaveOnline = rand(10,20);

        if ($timeNow->hour > 6 && $timeNow->hour < 16) {
            $numberOfBotsToHaveOnline = rand(20, 35);
        } elseif (($timeNow->hour >= 18 && $timeNow->hour <= 23) || ($timeNow->hour >= 0 && $timeNow->hour <= 6)) {
            $numberOfBotsToHaveOnline = rand(35, 45);
        }

        $schedule->command('bots:set-random-online ' . $numberOfBotsToHaveOnline)->everyTenMinutes();
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
