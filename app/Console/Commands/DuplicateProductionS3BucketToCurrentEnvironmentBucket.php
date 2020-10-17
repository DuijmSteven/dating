<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DuplicateProductionS3BucketToCurrentEnvironmentBucket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:s3:duplicate-production-bucket-to-current-environment-bucket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copies content of production s3 bucket to current env s3 bucket';

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
        if (config('app.env') === 'production') {
            $this->warn('This command cannot be run in production');
            \Log::debug('This command (' . $this->signature . ') cannot be run in production');
        }

        ini_set('max_execution_time', 500);

        $localBucket = config('filesystems.disks.cloud.bucket');
        $productionBucket = config('filesystems.disks.cloud.production_bucket');

        $this->info('Bucket name: ' . $localBucket);

        try {
            $this->info('Starting to empty s3://' . $localBucket . '...');
            \Log::debug('Starting to empty s3://' . $localBucket . '...');

            // run the cli job
            $emptyCurrentEnvBucket = Process::fromShellCommandline('/usr/local/bin/aws s3 rm s3://' . $localBucket . ' --recursive');
            $emptyCurrentEnvBucket->setTimeout(500);
            $emptyCurrentEnvBucket->run();

            if ($emptyCurrentEnvBucket->isSuccessful()) {
                $this->info('Finished emptying s3://' . $localBucket . '.');
                \Log::debug('Finished emptying s3://' . $localBucket . '.');

                $this->info('Starting to copy files from s3://' . $productionBucket . ' to s3://' . $localBucket . '...');
                \Log::debug('Starting to copy files from s3://' . $productionBucket . ' to s3://' . $localBucket . '...');

                $copyProductionBucketToCurrentEnvBucket = Process::fromShellCommandline('/usr/local/bin/aws s3 sync s3://' . $productionBucket . '  s3://' . $localBucket . '');
                $copyProductionBucketToCurrentEnvBucket->setTimeout(1000);
                $copyProductionBucketToCurrentEnvBucket->run();

                if ($copyProductionBucketToCurrentEnvBucket->isSuccessful()) {
                    $this->info('Finished copying files from s3://' . $productionBucket . ' to s3://' . $localBucket . '.');
                    \Log::debug('Finished copying files from s3://' . $productionBucket . ' to s3://' . $localBucket . '.');
                } else {
                    $this->info('There was an error copying files from s3://' . $productionBucket . ' to s3://' . $localBucket . '.');
                    \Log::debug('There was an error copying files from s3://' . $productionBucket . ' to s3://' . $localBucket . '.');
                }
            }
            else {
                $this->info('There was an error copying files from s3://' . $productionBucket . ' to s3://' . $localBucket . '.');
                \Log::debug('There was an error copying files from s3://' . $productionBucket . ' to s3://' . $localBucket . '.');
                \Log::debug($emptyCurrentEnvBucket->getErrorOutput());
                \Log::debug($emptyCurrentEnvBucket->getExitCodeText());
                \Log::debug($emptyCurrentEnvBucket->getOutput());

                throw new ProcessFailedException($emptyCurrentEnvBucket);
            }
        }
        catch (\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}
