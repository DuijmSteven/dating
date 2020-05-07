<?php

namespace App\Console\Commands;

use App\EmailType;
use App\Mail\ProfileViewed;
use App\Managers\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendProfileViewedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:emails:send-profile-viewed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends profile viewed notification emails to users';
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
                $query->where('gender', 1);
                $query->where('looking_for_gender', 2);
                $query->where('city', '!=', null);
                $query->where('about_me', '!=', null);
            })
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('emailTypes', function ($query) {
                $query->where('id', EmailType::PROFILE_VIEWED);
            })
            ->whereDoesntHave('emailTypeInstances', function ($query) use ($timeNow) {
                $query->where('email_type_id', EmailType::PROFILE_VIEWED);
                $query->where('created_at', '>=', Carbon::now('Europe/Amsterdam')->subHours(3)->toDateTimeString());
            })
            ->where('active', true)
            ->get();

            /** @var User $user */
            foreach ($users as $user) {
                try {
                    $number = rand(1, 1000);

                    if ($number === 1) {
                        $this->userManager->setProfileViewedEmailAndStoreView($user);
                    }
                } catch (\Exception $exception) {
                    \Log::error(__CLASS__ . ' - ' . $exception->getMessage());
                    continue;
                }

            }
        }
    }
}
