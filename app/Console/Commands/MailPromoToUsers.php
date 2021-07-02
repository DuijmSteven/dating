<?php

namespace App\Console\Commands;

use App\Creditpack;
use App\EmailType;
use App\Mail\DatingsitelijstPromo;
use App\Mail\MessageReceived;
use App\Mail\PleaseComeBack;
use App\Payment;
use App\Role;
use App\User;
use App\UserMeta;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailPromoToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-promo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mails datingsitelijst promo to users';

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

        $orestis = User::find(233);

        $email =
            (new DatingsitelijstPromo(
                $orestis
            ))
                ->from('info@datingsitelijst.nl')
                ->onQueue('emails');

        Mail::to(['orestis.palampougioukis@gmail.com', 'apmavrid@gmail.com'])
            ->queue($email);

//        $mailablePeasants = User
//            ::whereHas('roles', function ($query) {
//                $query->where('id', Role::ROLE_PEASANT);
//            })
//            ->get();
//
//        $emailDelay = 0;
//        $loopCount = 0;
//
//        /** @var User $peasant */
//        foreach ($mailablePeasants as $peasant) {
//            if ($loopCount % 5 === 0) {
//                $emailDelay++;
//            }
//
//            if (config('app.env') === 'production') {
//                $email =
//                    (new DatingsitelijstPromo(
//                        $peasant
//                    ))
//                    ->from('info@datingsitelijst.nl')
//                    ->delay($emailDelay)
//                    ->onQueue('emails');
//
//                Mail::to($peasant)
//                    ->queue($email);
//            }
//
//            $peasant->emailTypeInstances()->attach(EmailType::DATINGSITELIJSTPROMO, [
//                'email' => $peasant->getEmail(),
//                'email_type_id' => EmailType::DATINGSITELIJSTPROMO
//            ]);
 //       }
    }
}
