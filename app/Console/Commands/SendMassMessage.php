<?php

namespace App\Console\Commands;

use App\Conversation;
use App\ConversationMessage;
use App\EmailType;
use App\Mail\MessageReceived;
use App\Managers\UserManager;
use App\OpenConversationPartner;
use App\PastMassMessage;
use App\Payment;
use App\User;
use App\UserMeta;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Kim\Activity\Activity;

class SendMassMessage extends Command
{
    use InteractsWithQueue;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass-message:send {body} {limitation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends mass message to a set of users determined by the limitation';

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
        if ($this->attempts() > 1) {
            \Log::debug('Send mass message job attempted to execute a second time. Cancelling...');
            $this->fail();
            return false;
        }

        $messageBody = $this->argument('body');
        $limitMessage = $this->argument('limitation');

        $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();

        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
        $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');
        $startOfFourDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(4)->startOfDay()->setTimezone('UTC');

        $usersQuery = User::with(['meta'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            });

        if ($limitMessage === 'unlimited') {
            // temporary solution
            $usersQuery->where('exclude_from_mass_messages', '=', false);
        }

        if ($limitMessage === 'limited_with_pic') {
            $usersQuery->where('exclude_from_mass_messages', '=', false);

            $usersQuery->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '!=', null);
                    $query->orWhere('city', '!=', null);
                })
                    ->orWhereHas('images');
            });
        } else if ($limitMessage === 'limited_no_pic') {
            $usersQuery->where('exclude_from_mass_messages', '=', false);

            $usersQuery
                ->whereDoesntHave('images')
                ->where(function ($query) {
                    $query->whereHas('meta', function ($query) {
                        $query->where('dob', '=', null);
                        $query->where('city', '=', null);
                        $query->where('eye_color', '=', null);
                        $query->where('hair_color', '=', null);
                    });
                });
        } else if ($limitMessage === 'limited_have_payed') {

            // temporary solution
            $usersQuery->where('exclude_from_mass_messages', '=', false);

            $usersQuery
                ->whereHas('payments', function ($query) {
                    $query->where('status', Payment::STATUS_COMPLETED);
                });
        } else if ($limitMessage === 'limited_have_payed_and_no_images') {

            // temporary solution
            $usersQuery->where('exclude_from_mass_messages', '=', false);

            $usersQuery
                ->whereHas('payments', function ($query) {
                    $query->where('status', Payment::STATUS_COMPLETED);
                })
                ->whereDoesntHave('images');
        } else if ($limitMessage === 'limited_today') {
            $usersQuery
                ->whereBetween('created_at', [$startOfToday, $endOfToday]);
        } else if ($limitMessage === 'limited_yesterday') {
            $usersQuery
                ->whereBetween('created_at', [$startOfYesterday, $endOfYesterday]);
        } else if ($limitMessage === 'limited_yesterday_up_to_four_days_ago') {
            $usersQuery
                ->whereBetween('created_at', [$startOfFourDaysAgo, $endOfYesterday]);
        }

        $users = $usersQuery->get();

        $emailDelay = 0;
        $loopCount = 0;
        $errorsCount = 0;
        $unmailableCount = 0;

        /** @var User $user */
        foreach ($users as $user) {
            if ($loopCount % 5 === 0) {
                $emailDelay++;
            }

            $radiusSearch = false;

            // determine location for radius search
            if ($user->meta->country === 'be') {
                $radiusSearch = true;

                $lat = 51.030916;
                $lng = 4.622086;

                $latInRadians = deg2rad($lat);
                $lngInRadians = deg2rad($lng);

                $angularRadius = 110 / 6371;

                $latMin = rad2deg($latInRadians - $angularRadius);
                $latMax = rad2deg($latInRadians + $angularRadius);

                $deltaLng = asin(sin($angularRadius) / cos($latInRadians));

                $lngMin = rad2deg($lngInRadians - $deltaLng);
                $lngMax = rad2deg($lngInRadians + $deltaLng);
            }

            try {
                \DB::beginTransaction();

                $query = User::where('active', true);

                if ($radiusSearch) {
                    $query->join('user_meta as um', 'users.id', '=', 'um.user_id')
                        ->select('users.*');
                }

                $query
                    ->whereHas('meta', function ($query) use ($user) {
                        $query->where('looking_for_gender', $user->meta->gender);
                        $query->where('gender', $user->meta->looking_for_gender);
                    });

                if ($radiusSearch) {
                    $query->whereHas('meta', function ($query) use (
                        $latMin,
                        $latMax,
                        $lngMin,
                        $lngMax
                    ) {
                        $query->where('lat', '>=', $latMin)
                            ->where('lat', '<=', $latMax)
                            ->where('lng', '>=', $lngMin)
                            ->where('lng', '<=', $lngMax);
                    });
                }

                $query->whereHas('roles', function ($query) {
                        $query->where('id', User::TYPE_BOT);
                    })
                    ->whereDoesntHave('conversationsAsUserA', function ($query) use ($user) {
                        $query->where('user_b_id', $user->getId());
                    })
                    ->whereDoesntHave('conversationsAsUserB', function ($query) use ($user) {
                        $query->where('user_a_id', $user->getId());
                    })
                    ->orderBy(\DB::raw('RAND()'));

                $bot = $query->first();

                if (!($bot instanceof User)) {
                    continue;
                }

                $conversation = new Conversation([
                    'user_a_id' => $bot->getId(),
                    'user_b_id' => $user->getId()
                ]);

                $conversation->setNewActivityForUserB(true);
                $conversation->setUpdatedAt(Carbon::now());
                $conversation->save();

                $messageInstance = new ConversationMessage([
                    'conversation_id' => $conversation->getId(),
                    'type' => 'generic',
                    'sender_id' => $bot->getId(),
                    'recipient_id' => $user->getId(),
                    'body' => $messageBody,
                    'has_attachment' => false,
                    'operator_id' => null,
                    'operator_message_type' => null
                ]);

                $messageInstance->save();

                $recipientPartnerIds = OpenConversationPartner::where('user_id', $user->getId())
                    ->get()
                    ->pluck('partner_id')
                    ->toArray();

                $recipientOpenConversationPartnersCount = count($recipientPartnerIds);

                if (!in_array($bot->getId(), $recipientPartnerIds) && $recipientOpenConversationPartnersCount < 2) {
                    $user->addOpenConversationPartner($bot, 1);
                }

                $recipientEmailTypeIds = $user->emailTypes->pluck('id')->toArray();

                $recipientHasMessageNotificationsEnabled = in_array(
                    EmailType::MESSAGE_RECEIVED,
                    $recipientEmailTypeIds
                );

                if (
                    $recipientHasMessageNotificationsEnabled &&
                    !in_array($user->getId(), $onlineUserIds)
                ) {
                    if ($user->isMailable) {
                        if (config('app.env') === 'production') {
                            $messageReceivedEmail =
                                (new MessageReceived(
                                    $bot,
                                    $user,
                                    $messageBody,
                                    false
                                ))
                                    ->delay($emailDelay)
                                    ->onQueue('emails');

                            Mail::to($user)
                                ->queue($messageReceivedEmail);
                        }

                        $user->emailTypeInstances()->attach(EmailType::MESSAGE_RECEIVED, [
                            'email' => $user->getEmail(),
                            'email_type_id' => EmailType::MESSAGE_RECEIVED,
                            'actor_id' => $bot->getId()
                        ]);
                    } else {
                        $unmailableCount++;
                    }
                }

                $this->userManager->storeProfileView(
                    $bot,
                    $user,
                    UserView::TYPE_BOT_MESSAGE
                );

                \DB::commit();
            } catch (\Exception $exception) {
                $errorsCount++;

                \DB::rollBack();

                \Log::info(__CLASS__ . ' - ' . $exception->getMessage());
            }

            $loopCount++;
        }

        $pastMassMessageInstance = new PastMassMessage();
        $pastMassMessageInstance->setBody($messageBody);
        $pastMassMessageInstance->setUserCount($users->count() - $errorsCount);
        $pastMassMessageInstance->save();

        if ($errorsCount) {
            \Log::debug(($users->count() - $errorsCount) . ' messages were sent and ' . $errorsCount . ' messages were not sent due to errors, ' . $unmailableCount . ' not sent due to the emails being un-mailable. ' . $unmailableCount . ' emails were not sent to un-mailable accounts.');

        } else {
            \Log::debug($users->count() . ' messages were sent successfully. ' . $unmailableCount . ' emails were not sent to un-mailable accounts');
        }
    }
}
