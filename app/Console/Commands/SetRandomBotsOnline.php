<?php

namespace App\Console\Commands;

use App\Managers\UserManager;
use Illuminate\Console\Command;

class SetRandomBotsOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bots:set-random-online {botAmount=40}';

    /**
     * The console command description.
     *ExportDb
     * @var string
     */
    protected $description = 'Sets an amount of random users online. The amount of users can be passed as a parameter';

    /** @var UserManager  */
    private $userManager;


    /**
     * SetRandomUsersOnline constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $botAmount = $this->argument('botAmount');

        \Log::debug('Setting amount of bots online: ' . $botAmount);

        $this->userManager->setRandomBotsOnline($botAmount);
    }
}
