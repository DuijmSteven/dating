<?php

namespace App\Console\Commands;

use App\Creditpack;
use App\EmailType;
use App\Mail\MessageReceived;
use App\Mail\PleaseComeBack;
use App\Payment;
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
    protected $signature = 'send-discount:inactive-peasants {daysInactive} {discountPercentage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets a discount percentage in the DB for users that are inactive for a certain amount of days and emails them';

    public $timeout = 0;

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
        $daysInactive = $this->argument('daysInactive');
        $discountPercentage = $this->argument('discountPercentage');

        $creditpack = Creditpack::where('name', '!=', 'test')->orderBy('id')->get();

        $inactiveMailablePeasants = User
            ::whereHas('roles', function ($query) {
                $query->where('id', Role::ROLE_PEASANT);
            })
            ->whereDoesntHave('emailTypeInstances', function ($query) {
                $query->where('email_type_id', EmailType::PLEASE_COME_BACK)
                    ->where('created_at', '>=', Carbon::now()->subDays(7)->format('Y-m-d'));
            })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('email_verified', UserMeta::EMAIL_VERIFIED_DELIVERABLE)
                    ->orWhere('email_verified', UserMeta::EMAIL_VERIFIED_RISKY);
            })
            ->where(function ($query) use ($discountPercentage) {
                $query
                    ->where('discount_percentage', null)
                    ->orWhere('discount_percentage', '<=', $discountPercentage);
            })
            ->where(function ($query) use ($daysInactive) {
                $query
                    ->where(function ($query) use ($daysInactive) {
                        $query
                            ->where('created_at', '<=', Carbon::now()->subDays($daysInactive))
                            ->whereDoesntHave('payments', function ($query) {
                                $query->where('status', Payment::STATUS_COMPLETED);
                            });
                    })
                    ->orWhere(function ($query) use ($daysInactive) {
                        $query
                            ->whereHas('payments', function ($query) {
                                $query->where('status', Payment::STATUS_COMPLETED);
                            })
                            ->whereHas('account', function ($query) {
                                $query->where('credits', '=', 0);
                            })
                            ->whereDoesntHave('payments', function ($query) use ($daysInactive) {
                                $query->where('discount_percentage', '!=', null)
                                    ->where('created_at', '>=', Carbon::now()->subDays(45)->format('Y-m-d'));
                            })
                            ->whereHas('messages', function ($query) use ($daysInactive) {
                                $query->where('created_at', '<', Carbon::now()->subDays($daysInactive));
                            })
                            ->whereDoesntHave('messages', function ($query) use ($daysInactive) {
                                $query->where('created_at', '>=', Carbon::now()->subDays($daysInactive));
                            });
                    });
            })->get();

        $emailDelay = 0;
        $loopCount = 0;

        /** @var User $peasant */
        foreach ($inactiveMailablePeasants as $peasant) {
            if ($loopCount % 5 === 0) {
                $emailDelay++;
            }

            if (config('app.env') === 'production') {
                $email =
                    (new PleaseComeBack(
                        $peasant,
                        $creditpack
                    ))
                    ->delay($emailDelay)
                    ->onQueue('emails');

                Mail::to($peasant)
                    ->queue($email);
            }

            $peasant->setDiscountPercentage($discountPercentage);
            $peasant->save();

            $peasant->emailTypeInstances()->attach(EmailType::PLEASE_COME_BACK, [
                'email' => $peasant->getEmail(),
                'email_type_id' => EmailType::PLEASE_COME_BACK
            ]);
        }
    }
}
