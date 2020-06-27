<?php

namespace App\Console\Commands;

use App\Services\EmailVerificationService;
use App\User;
use App\UserMeta;
use Illuminate\Console\Command;

class VerifyPendingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:verify-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var EmailVerificationService
     */
    private EmailVerificationService $emailVerificationService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EmailVerificationService $emailVerificationService
    )
    {
        parent::__construct();
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        \Log::debug('Checking for users tha have pending verification email...');

        $users = User::with(['meta'])
        ->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
        ->whereHas('meta', function ($query) {
            $query->where('email_verified', UserMeta::EMAIL_VERIFIED_PENDING);
        })
        ->take(30)
        ->get();

        \Log::debug('User count: ' . $users->count());

        foreach ($users as $user) {
            $result = $this->emailVerificationService->verifySingleEmail($user->getEmail());

            $this->emailVerificationService->setUserMetaFromVerificationResult(
                $user,
                $result
            );
        }

        \Log::debug('...done');
    }
}
