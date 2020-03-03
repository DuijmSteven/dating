<?php

namespace App\Console\Commands;

use App\EmailType;
use App\Mail\ProfileViewed;
use App\Managers\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendProfileCompletionEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:emails:send-profile-completion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends profile completion notification emails to users';
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
        $users = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })->whereHas('emailTypes', function ($query) {
            $query->where('id', EmailType::GENERAL);
        })->whereDoesntHave('emailTypeInstances', function ($query) {
            $query->where('email_type_id', EmailType::PROFILE_COMPLETION);
            $query->where('created_at', '>=', Carbon::now('Europe/Amsterdam')->subDays(10)->toDateTimeString());
        })
            ->whereDoesntHave('profileImage')
            ->where('created_at', '<=', Carbon::now('Europe/Amsterdam')->subDays(1)->toDateTimeString())
            ->where('created_at', '>=', Carbon::now('Europe/Amsterdam')->subDays(60)->toDateTimeString())
            ->where('active', true)
            ->get();

        /** @var User $user */
        foreach ($users as $user) {
            if (!$user->isPayingUser()) {
                $this->userManager->setProfileCompletionEmail($user);
            }
        }
    }
}
