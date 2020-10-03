<?php

namespace App\Console\Commands;

use App\EmailType;
use App\Managers\UserManager;
use App\User;
use App\UserMeta;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetProfileViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:emails:set-profile-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set automated profile views from bots to users';
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
      UserManager $userManager
    ) {
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
        $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

        if ($timeNow->hour >= 8 || $timeNow->hour <= 2) {
            $users = User::whereHas('meta', function ($query) {
                $query->where('email', '!=', null);
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
                $query->where('city', '!=', null);
                $query->where('about_me', '!=', null);
            })
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereDoesntHave('views', function ($query) use ($timeNow) {
                $query->where('created_at', '>=', Carbon::now('Europe/Amsterdam')->subHours(2)->toDateTimeString());
            })
            ->where('active', true)
            ->get();

            /** @var User $user */
            foreach ($users as $user) {
                try {
                    $number = rand(1, 1000);

                    if ($number === 1) {
                        $viewerBot = $this->userManager->pickBotToProfileViewPeasant($user);

                        $this->userManager->storeProfileView(
                            $viewerBot,
                            $user,
                            UserView::TYPE_SCHEDULED
                        );
                    }
                } catch (\Exception $exception) {
                    \Log::error(__CLASS__ . ' - ' . $exception->getMessage());
                    continue;
                }
            }
        }
    }
}
