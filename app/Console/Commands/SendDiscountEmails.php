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

        \Log::info('SendDiscountEmails starting.....');

        $this->call('send-discount:inactive-peasants', ['daysInactive' => 30, 'discountPercentage' => 40]);
        $this->call('send-discount:inactive-peasants', ['daysInactive' => 20, 'discountPercentage' => 30]);
        $this->call('send-discount:inactive-peasants', ['daysInactive' => 10, 'discountPercentage' => 20]);
        $this->call('send-discount:inactive-peasants', ['daysInactive' => 5, 'discountPercentage' => 15]);
        $this->call('send-discount:inactive-peasants', ['daysInactive' => 2, 'discountPercentage' => 10]);

        \Log::info('SendDiscountEmails finished.....');
    }
}
