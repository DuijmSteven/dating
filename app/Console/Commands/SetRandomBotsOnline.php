<?php

namespace App\Console\Commands;

use App\Managers\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetRandomBotsOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bots:set-random-online';

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
    public function handle(): void
    {
        $botAmount = $this->argument('botAmount');

        \Log::debug('Starting set online bots command...');

        $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

        $femaleBotCount = User::with(['roles', 'meta'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_FEMALE);
            })
            ->where('active', true)
            ->count();

        \Log::debug($femaleBotCount, 'femaleBotCount');

        $numberOfBotsToHaveOnline = rand(
            round(3/100 * $femaleBotCount),
            round(6/100 * $femaleBotCount)
        );

        if ($timeNow->hour > 6 && $timeNow->hour <= 8) {
            $numberOfBotsToHaveOnline = rand(
                round(5/100 * $femaleBotCount),
                round(11/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour > 8 && $timeNow->hour < 18) {
            $numberOfBotsToHaveOnline = rand(
                round(6/100 * $femaleBotCount),
                round(12/100 * $femaleBotCount),
            );
        } elseif ($timeNow->hour >= 18 && $timeNow->hour <= 22) {
            $numberOfBotsToHaveOnline = rand(
                round(15/100 * $femaleBotCount),
                round(20/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour > 22 && $timeNow->hour <= 23) {
            $numberOfBotsToHaveOnline = rand(
                round(13/100 * $femaleBotCount),
                round(17/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour >= 0 && $timeNow->hour <= 1) {
            $numberOfBotsToHaveOnline = rand(
                round(9/100 * $femaleBotCount),
                round(17/100 * $femaleBotCount)
            );
        } elseif ($timeNow->hour > 2 && $timeNow->hour <= 6) {
            $numberOfBotsToHaveOnline = rand(
                0,
                10
            );
        }

        \Log::debug('Setting ' . $numberOfBotsToHaveOnline . ' female bots online');
        $this->userManager->setRandomBotsOnline($numberOfBotsToHaveOnline);

        \Log::debug('Finished running set online bots command');
    }
}
