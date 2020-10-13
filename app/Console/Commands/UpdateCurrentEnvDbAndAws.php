<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateCurrentEnvDbAndAws extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current-env:update-db-and-aws';

    /**
     * The console command description.
     *ExportDb
     * @var string
     */
    protected $description = 'Copy AWS production bucket to the current env bucket and import latest production DB export';

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
        set_time_limit(500);

        if (!in_array(config('app.env'), ['staging', 'local'])) {
            $this->error('This command cannot be run on the production environment');
            $this->warn('Stopping execution...');
            return false;
        }

        $this->call('aws:s3:duplicate-production-bucket-to-current-environment-bucket');
        $this->call('db:current-env:import-latest-production-export');
    }
}
