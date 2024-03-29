<?php

namespace App\Console;

use App\Console\Commands\CheckRecentStartedPayments;
use App\Console\Commands\CheckXpartnersLeadsWIthPendingEligibilityStatus;
use App\Console\Commands\CopyBotDataFromS3BucketToOtherS3Bucket;
use App\Console\Commands\CreateDiscountForInactiveUsersAndMailThem;
use App\Console\Commands\ExportDb;
use App\Console\Commands\MailPromoToUsers;
use App\Console\Commands\SendDiscountEmails;
use App\Console\Commands\SendMassMessage;
use App\Console\Commands\SetProfileViews;
use App\Console\Commands\UpdateCurrentEnvDbAndAws;
use App\Console\Commands\ValidateEligibleXpartnersLeads;
use App\Console\Commands\VerifyPendingEmails;
use App\Mail\DatingsitelijstPromo;
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
        Commands\SetProfileViews::class,
        Commands\DuplicateProductionS3BucketToCurrentEnvironmentBucket::class,
        Commands\UpdateCurrentEnvDbAndAws::class,
        Commands\SendProfileCompletionEmails::class,
        CopyBotDataFromS3BucketToOtherS3Bucket::class,
        CheckRecentStartedPayments::class,
        SendMassMessage::class,
        CheckXpartnersLeadsWIthPendingEligibilityStatus::class,
        ValidateEligibleXpartnersLeads::class,
        VerifyPendingEmails::class,
        CreateDiscountForInactiveUsersAndMailThem::class,
        SendDiscountEmails::class,
        MailPromoToUsers::class
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

        $schedule->command(SetProfileViews::class)->everyMinute();

        if (config('app.env') === 'production') {
//            if (config('app.site_id') === SiteHelper::DATEVRIJ_NL) {
//                $schedule->command(SendProfileCompletionEmails::class)->dailyAt("19:00");
//            }

            $schedule->command(SendDiscountEmails::class)->dailyAt("17:00");

            $schedule->command(ExportDb::class)->dailyAt("03:30");
            $schedule->command(CheckRecentStartedPayments::class)->everyMinute();
            $schedule->command(VerifyPendingEmails::class)->everyMinute();
        }
        
        if (config('app.env') === 'staging') {
            $schedule->command(UpdateCurrentEnvDbAndAws::class)->dailyAt("04:10");
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
