<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CopyBotDataFromS3BucketToOtherS3Bucket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:s3:copy-bot-data-from-bucket-to-bucket {from} {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies inactive bot images from bucket to bucket';

    public $timeout = 0;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 500);

        $fromBucket = $this->argument('from');
        $toBucket = $this->argument('to');

        $botsToCopy = User
            ::whereHas('roles', function ($query) {
                $query->where('id', Role::ROLE_BOT);
            })
            ->where('active', false)
            ->get();

        try {
            $successfullyCopiedCount = 0;
            $errorCount = 0;

            foreach ($botsToCopy as $bot) {

            }

            $copyProductionBucketToCurrentEnvBucket = Process::fromShellCommandline(
                '/usr/local/bin/aws s3 sync s3://' . $fromBucket . '/users/' . $bot->getId() . '/images' . '  s3://' . $toBucket . '/users/' . $bot->getId() . '/images'
            );
            $copyProductionBucketToCurrentEnvBucket->setTimeout(1000);
            $copyProductionBucketToCurrentEnvBucket->run();

            if ($copyProductionBucketToCurrentEnvBucket->isSuccessful()) {
                $successfullyCopiedCount++;
            } else {
                $errorCount++;
            }
        }
        catch (\Exception $e)
        {
            $this->info($e->getMessage());
        }

        $this->info($successfullyCopiedCount . ' bots were successfully copied and ' . $errorCount . ' were not.');

    }
}
