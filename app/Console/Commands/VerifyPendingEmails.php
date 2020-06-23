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
        ->whereHas('meta', function ($query) {
            $query->where('email_verification_status', UserMeta::EMAIL_VERIFICATION_STATUS_PENDING);
        })
        ->take(30)
        ->get();

        \Log::debug('User count: ' . $users->count());

        foreach ($users as $user) {

            $result = $this->emailVerificationService->verifySingleEmail($user->getEmail());

            if ($result === EmailVerificationService::VALID_EMAIL_RESULT) {
                $user->meta->setEmailVerificationStatus(
                    UserMeta::EMAIL_VERIFICATION_STATUS_DONE
                );

                $user->meta->setEmailVerified(
                    UserMeta::EMAIL_VERIFIED_TRUE
                );
            } elseif ($result === EmailVerificationService::INVALID_EMAIL_RESULT) {
                $user->meta->setEmailVerificationStatus(
                    UserMeta::EMAIL_VERIFICATION_STATUS_DONE
                );

                $user->meta->setEmailVerified(
                    UserMeta::EMAIL_VERIFIED_FALSE
                );
            } elseif ($result === EmailVerificationService::RISKY_EMAIL_RESULT) {
                $user->meta->setEmailVerificationStatus(
                    UserMeta::EMAIL_VERIFICATION_STATUS_DONE
                );

                $user->meta->setEmailVerified(
                    UserMeta::EMAIL_VERIFIED_RISKY
                );
            } elseif ($result === EmailVerificationService::UNKNOWN_EMAIL_RESULT) {
                $user->meta->setEmailVerificationStatus(
                    UserMeta::EMAIL_VERIFICATION_STATUS_DONE
                );

                $user->meta->setEmailVerified(
                    UserMeta::EMAIL_VERIFIED_UNKNOWN
                );
            } elseif ($result === EmailVerificationService::ERROR_RESULT) {
                $user->meta->setEmailVerificationStatus(
                    UserMeta::EMAIL_VERIFICATION_STATUS_FAILED
                );
            } else {
                $user->meta->setEmailVerificationStatus(
                    UserMeta::EMAIL_VERIFICATION_STATUS_DONE
                );

                $user->meta->setEmailVerified(
                    UserMeta::EMAIL_VERIFIED_OTHER
                );
            }

            $user->meta->save();
        }

        \Log::debug('...done');
    }
}
