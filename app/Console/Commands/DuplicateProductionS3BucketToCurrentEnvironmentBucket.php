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
    protected $description = 'Copies content of altijdsex production s3 bucket to staging.altijdsex staging s3 bucket';

    public $timeout = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
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

        $bucketName = config('filesystems.disks.cloud.bucket');

        $this->info('Bucket name: ' . $bucketName);

        try {
            $this->info('Starting to empty s3://' . $bucketName . '...');
            \Log::debug('Starting to empty s3://' . $bucketName . '...');

            // run the cli job
            $emptyCurrentEnvBucket = new Process('/usr/local/bin/aws s3 rm s3://' . $bucketName . ' --recursive');
            $emptyCurrentEnvBucket->run();

            if ($emptyCurrentEnvBucket->isSuccessful()) {
                $this->info('Finished emptying s3://' . $bucketName . '.');
                \Log::debug('Finished emptying s3://' . $bucketName . '.');

                $this->info('Starting to copy files from s3://altijdsex to s3://' . $bucketName . '...');
                \Log::debug('Starting to copy files from s3://altijdsex to s3://' . $bucketName . '...');

                $copyProductionBucketToCurrentEnvBucket = new Process('/usr/local/bin/aws s3 sync s3://altijdsex s3://' . $bucketName . '');
                $copyProductionBucketToCurrentEnvBucket->run();

                if ($copyProductionBucketToCurrentEnvBucket->isSuccessful()) {
                    $this->info('Finished copying files from s3://altijdsex to s3://' . $bucketName . '.');
                    \Log::debug('Finished copying files from s3://altijdsex to s3://' . $bucketName . '.');
                } else {
                    $this->info('There was an error copying files from s3://altijdsex to s3://' . $bucketName . '.');
                    \Log::debug('There was an error copying files from s3://altijdsex to s3://' . $bucketName . '.');
                }
            }
            else {
                $this->info('There was an error copying files from s3://altijdsex to s3://' . $bucketName . '.');
                \Log::debug('There was an error copying files from s3://altijdsex to s3://' . $bucketName . '.');
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
