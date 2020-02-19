<?php

namespace App\Console\Commands;

use App\Managers\UserManager;
use Illuminate\Console\Command;

class SetRandomUsersOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:set-random-online {userAmount=40}';

    /**
     * The console command description.
     *
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
        $userAmount = $this->argument('userAmount');
        $this->userManager->setRandomUsersOnline($userAmount);
    }
}
