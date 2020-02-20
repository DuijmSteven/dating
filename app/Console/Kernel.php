<?php

namespace App\Console;

use App\Console\Commands\ExportDb;
use App\Console\Commands\SetRandomBotsOnline;
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

        $numberOfBotsOnlineNow = Activity::users(1)->count();
        \Log::debug('online now: ' .$numberOfBotsOnlineNow);

        $numberOfBotsToHaveOnline = rand(30, 45);

        \Log::debug('to have: ' . $numberOfBotsToHaveOnline);


        $numberOfBotsToSetOnline = $numberOfBotsToHaveOnline - $numberOfBotsOnlineNow;

        \Log::debug('to set: '.  $numberOfBotsToSetOnline);


        if ($numberOfBotsToSetOnline > 0) {
            $schedule->command('bots:set-random-online ' . $numberOfBotsToSetOnline)->everyMinute();
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
