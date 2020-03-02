<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ImportLatestProductionDbExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:current-env:import-latest-production-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the latest production DB export that exists in AWS';

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
     * @throws \Exception
     */
    public function handle()
    {
        set_time_limit(500);

        if (!in_array(config('app.env'), ['staging', 'local'])) {
            $this->error('This command cannot be run on the production environment');
            $this->warn('Stopping execution...');
            return false;
        }

        $tempLocation   = '/tmp/' . config('database.connections.mysql.database') . '_' . date("Y-m-d_Hi") . '.sql';

        try {

            $s3Prod = \Storage::disk('cloud_prod');
            $allFiles = $s3Prod->allFiles('/mysql/');

            $fileToUse = null;

            foreach ($allFiles as $file) {
                if (Str::contains($file, date("Y-m-d"))) {
                    $fileToUse = $file;

                    $this->info('Found today\'s production DB export: ' . $fileToUse);
                }
            }

            if (!$fileToUse) {
                $this->warn('Could not find today\'s production DB export. Stopping execution...');
                return false;
            }

            $s3FileContents = $s3Prod->get($fileToUse);

            file_put_contents($tempLocation, $s3FileContents);

            $dropDbprocess = new Process(sprintf(
                'mysqladmin -u%s -p%s drop --force %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database')
            ));

            $dropDbprocess->run();

            if ($dropDbprocess->isSuccessful()) {
                $this->info('The DB was dropped');

            } else {
                $this->warn('The DB was not dropped');
            }

            $createDbProcess = new Process(sprintf(
                'mysqladmin -u%s -p%s create %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database')
            ));

            $createDbProcess->run();

            if ($createDbProcess->isSuccessful()) {
                $this->info('The DB was created');

            } else {
                $this->warn('The DB was not created');
            }

            $importProductionDbProcess = new Process(sprintf(
                'mysql -u %s -p%s %s < %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $tempLocation
            ));

            $importProductionDbProcess->run();

            if ($importProductionDbProcess->isSuccessful()) {
                $this->info('The production DB export was imported successfully');

            } else {
                $this->warn('The production DB export was not imported');
            }

            @unlink($tempLocation);
        }
        catch (\Exception $e)
        {
            @unlink($tempLocation);
            $this->info($e->getMessage());
        }
    }
}
