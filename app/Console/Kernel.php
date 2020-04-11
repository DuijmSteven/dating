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
        $this->scheduleRandomOnlineBots($schedule);

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

    /**
     * @param Schedule $schedule
     */
    protected function scheduleRandomOnlineBots(Schedule $schedule): void
    {
        \Log::debug('Starting set online bots command...');

        $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

        $femaleBotCount = User::with(['roles', 'meta'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_FEMALE);
            })
            ->where('active', true)
            ->count();

        \Log::debug($femaleBotCount, ['$femaleBotCount']);

        $numberOfBotsToHaveOnline = rand(
            round(3/100 * $femaleBotCount),
            round(6/100 * $femaleBotCount)
        );

        if ($timeNow->hour > 6 && $timeNow->hour <= 8) {
            $numberOfBotsToHaveOnline = rand(
                round(5/100 * $femaleBotCount),
                round(11/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour > 8 && $timeNow->hour < 18) {
            $numberOfBotsToHaveOnline = rand(
                round(6/100 * $femaleBotCount),
                round(12/100 * $femaleBotCount),
            );
        } elseif ($timeNow->hour >= 18 && $timeNow->hour <= 22) {
            $numberOfBotsToHaveOnline = rand(
                round(15/100 * $femaleBotCount),
                round(20/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour > 22 && $timeNow->hour <= 23) {
            $numberOfBotsToHaveOnline = rand(
                round(13/100 * $femaleBotCount),
                round(17/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour >= 0 && $timeNow->hour <= 1) {
            $numberOfBotsToHaveOnline = rand(
                round(9/100 * $femaleBotCount),
                round(17/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour > 2 && $timeNow->hour <= 6) {
            $numberOfBotsToHaveOnline = rand(
                0,
                10
            );
        }

        \Log::debug('Setting ' . $numberOfBotsToHaveOnline . ' female bots online');

        $schedule->command('bots:set-random-online ' . $numberOfBotsToHaveOnline)->everyTenMinutes();

        \Log::debug('Finished running set online bots command');
    }
}
