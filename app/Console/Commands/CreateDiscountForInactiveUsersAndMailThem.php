<?php

namespace App\Console\Commands;

use App\EmailType;
use App\Mail\MessageReceived;
use App\Mail\PleaseComeBack;
use App\Role;
use App\User;
use App\UserMeta;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CreateDiscountForInactiveUsersAndMailThem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-discount:inactive-peasants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets a discount percentage in the DB for users that are inactive and emails them';

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
//        $inactiveMailablePeasants = User
//            ::whereHas('roles', function ($query) {
//                $query->where('id', Role::ROLE_PEASANT);
//            })
//            ->whereHas('meta', function ($query) {
//                $query->where('email_verified', UserMeta::EMAIL_VERIFIED_DELIVERABLE)
//                    ->orWhere('email_verified', UserMeta::EMAIL_VERIFIED_RISKY);
//            })
//            ->where(function ($query) {
//                $query->where('active', false)
//                    ->orWhere(function ($query) {
//                        $query
//                            ->whereDoesntHave('messages', function ($query) {
//                                $query->where('created_at', '>=', Carbon::now()->subMonths(4));
//                            });
//                    });
//            })->get();

        $inactiveMailablePeasants = User::whereIn('id', [233, 7746, 310])->get();

        $emailDelay = 0;
        $loopCount = 0;

        /** @var User $peasant */
        foreach ($inactiveMailablePeasants as $peasant) {
            if ($loopCount % 5 === 0) {
                $emailDelay++;
            }

            if (config('app.env') === 'staging') {
                $email =
                    (new PleaseComeBack(
                        $peasant
                    ))
                    ->delay($emailDelay)
                    ->onQueue('emails');

                Mail::to($peasant)
                    ->queue($email);
            }

            $peasant->setDiscountPercentage(15);
            $peasant->save();

            $peasant->emailTypeInstances()->attach(EmailType::PLEASE_COME_BACK, [
                'email' => $peasant->getEmail(),
                'email_type_id' => EmailType::PLEASE_COME_BACK
            ]);
        }
    }
}
