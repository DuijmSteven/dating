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

        $time = Carbon::now('Europe/Amsterdam'); // Current time
        $startOfMorning = Carbon::create($time->year, $time->month, $time->day, 6, 0, 0, 'Europe/Amsterdam'); //set time to 10:00
        $endOfMorning = Carbon::create($time->year, $time->month, $time->day, 18, 0, 0, 'Europe/Amsterdam'); //set time to 18:00
        $endOfEvening = Carbon::create($time->year, $time->month, $time->day, 2, 0, 0, 'Europe/Amsterdam'); //set time to 18:00

        $numberOfBotsToHaveOnline = rand(10,20);

        if (now('Europe/Amsterdam') > $startOfMorning && now('Europe/Amsterdam') <= $endOfMorning) {
            $numberOfBotsToHaveOnline = 10;
            //$numberOfBotsToHaveOnline = rand(20, 35);
        } elseif (now('Europe/Amsterdam') > $endOfMorning && now('Europe/Amsterdam') <= $endOfEvening) {
            $numberOfBotsToHaveOnline = 20;
            //$numberOfBotsToHaveOnline = rand(35, 45);
        } else {
            $numberOfBotsToHaveOnline = 30;
            //$numberOfBotsToHaveOnline = rand(10, 20);
        }

        $numberOfBotsOnlineNow = Activity::users(1)->count();
        \Log::debug('online now: ' . $numberOfBotsOnlineNow);

        //$numberOfBotsToHaveOnline = rand(30, 45);

        \Log::debug('to have: ' . $numberOfBotsToHaveOnline);


        $numberOfBotsToSetOnline = $numberOfBotsToHaveOnline - $numberOfBotsOnlineNow;

        \Log::debug('to set: ' . $numberOfBotsToSetOnline);

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
