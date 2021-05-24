<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SendDiscountEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-discount-emails';

    /**
     * The console command description.
     *ExportDb
     * @var string
     */
    protected $description = 'Sends discount emails to eligible users';

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

        if (config('app.env') !== 'production') {
            $this->error('This command can only be run on the production environment');
            $this->warn('Stopping execution...');
            return false;
        }

        $this->call('send-discount:inactive-peasants 30 50');
        $this->call('send-discount:inactive-peasants 20 30');
        $this->call('send-discount:inactive-peasants 10 20');
        $this->call('send-discount:inactive-peasants 3 15');
        $this->call('send-discount:inactive-peasants 1 10');
    }
}
