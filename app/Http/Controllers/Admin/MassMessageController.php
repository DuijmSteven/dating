<?php

namespace App\Http\Controllers\Admin;

use Activity;
use App\Http\Controllers\Controller;
use App\Managers\UserManager;
use App\PastMassMessage;
use App\Payment;
use App\Services\OnlineUsersService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MassMessageController extends Controller
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    public function __construct(
        UserManager $userManager,
        OnlineUsersService $onlineUsersService
    ) {
        parent::__construct($onlineUsersService);
        $this->userManager = $userManager;
    }

    public function new()
    {
        $unlimitedUsersQuery = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            });


        $unlimitedCount = $unlimitedUsersQuery->where('exclude_from_mass_messages', '=', false)->count();

        $withoutPicUsersQuery = clone $unlimitedUsersQuery;
        $withPicUsersQuery = clone $unlimitedUsersQuery;
        $havePayedUsersQuery = clone $unlimitedUsersQuery;
        $havePayedAndDontHaveImagesUsersQuery = clone $unlimitedUsersQuery;
        $registeredTodayUsersQuery = clone $unlimitedUsersQuery;
        $registeredYesterdayUsersQuery = clone $unlimitedUsersQuery;
        $registeredYesterdayUpTo4DaysAgoUsersQuery = clone $unlimitedUsersQuery;

        $registered5UpTo9DaysAgoUsersQuery = clone $unlimitedUsersQuery;
        $registered10UpTo19DaysAgoUsersQuery = clone $unlimitedUsersQuery;
        $registered20UpTo29DaysAgoUsersQuery = clone $unlimitedUsersQuery;
        $registered30UpTo39DaysAgoUsersQuery = clone $unlimitedUsersQuery;

        $withPicUsersQuery
            ->where('exclude_from_mass_messages', '=', false)
            ->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '!=', null);
                    $query->orWhere('city', '!=', null);
                })
                    ->orWhereHas('images');
            });

        $withPicCount = $withPicUsersQuery->count();

        $withoutPicUsersQuery
            ->where('exclude_from_mass_messages', '=', false)
            ->whereDoesntHave('images')
            ->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '=', null);
                    $query->where('city', '=', null);
                    $query->where('eye_color', '=', null);
                    $query->where('hair_color', '=', null);
                });
            });

        $withoutPicCount = $withoutPicUsersQuery->count();

        $havePayedUsersQuery
            ->where('exclude_from_mass_messages', '=', false)
            ->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            });

        $havePayedCount = $havePayedUsersQuery->count();

        $havePayedAndDontHaveImagesUsersQuery
            ->where('exclude_from_mass_messages', '=', false)
            ->whereHas('payments', function ($query) {
                $query->where('status', Payment::STATUS_COMPLETED);
            })
            ->whereDoesntHave('images');

        $havePayedAndDontHaveImagesCount = $havePayedAndDontHaveImagesUsersQuery->count();

        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
        $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
        $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');
        $startOfFourDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(4)->startOfDay()->setTimezone('UTC');

        $endOf5DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(5)->endOfDay()->setTimezone('UTC');
        $startOf9DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(9)->startOfDay()->setTimezone('UTC');

        $endOf10DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(10)->endOfDay()->setTimezone('UTC');
        $startOf19DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(19)->startOfDay()->setTimezone('UTC');

        $endOf20DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(20)->endOfDay()->setTimezone('UTC');
        $startOf29DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(29)->startOfDay()->setTimezone('UTC');

        $endOf30DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(30)->endOfDay()->setTimezone('UTC');
        $startOf39DaysAgo = Carbon::now('Europe/Amsterdam')->subDays(39)->startOfDay()->setTimezone('UTC');

        $registeredTodayUsersQuery
            ->whereBetween('created_at', [$startOfToday, $endOfToday]);

        $registeredTodayCount = $registeredTodayUsersQuery->count();

        $registeredYesterdayUsersQuery
            ->whereBetween('created_at', [$startOfYesterday, $endOfYesterday]);

        $registeredYesterdayCount = $registeredYesterdayUsersQuery->count();

        $registeredYesterdayUpTo4DaysAgoUsersQuery
            ->whereBetween('created_at', [$startOfFourDaysAgo, $endOfYesterday]);

        $registeredYesterdayUpToFourDaysAgoCount = $registeredYesterdayUpTo4DaysAgoUsersQuery->count();

        $registered5UpTo9DaysAgoUsersQuery
            ->whereBetween('created_at', [$startOf9DaysAgo, $endOf5DaysAgo]);

        $registered5UpTo9DaysAgoCount = $registered5UpTo9DaysAgoUsersQuery->count();

        $registered10UpTo19DaysAgoUsersQuery
            ->whereBetween('created_at', [$startOf19DaysAgo, $endOf10DaysAgo]);

        $registered10UpTo19DaysAgoCount = $registered10UpTo19DaysAgoUsersQuery->count();

        $registered20UpTo29DaysAgoUsersQuery
            ->whereBetween('created_at', [$startOf29DaysAgo, $endOf20DaysAgo]);

        $registered20UpTo29DaysAgoCount = $registered20UpTo29DaysAgoUsersQuery->count();

        $registered30UpTo39DaysAgoUsersQuery
            ->whereBetween('created_at', [$startOf39DaysAgo, $endOf30DaysAgo]);

        $registered30UpTo39DaysAgoCount = $registered30UpTo39DaysAgoUsersQuery->count();

        return view(
            'admin.mass-messages.new',
            [
                'title' => 'New Mass Message - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Mass Messages',
                'headingSmall' => 'New Mass Message',
                'pastMassMessages' => PastMassMessage::orderBy('created_at', 'desc')->get(),
                'carbonNow' => Carbon::now(),
                'userCounts' => [
                    'unlimited' => $unlimitedCount,
                    'withPic' => $withPicCount,
                    'withoutPic' => $withoutPicCount,
                    'havePayed' => $havePayedCount,
                    'havePayedAndDontHaveImages' => $havePayedAndDontHaveImagesCount,
                    'registeredToday' => $registeredTodayCount,
                    'registeredYesterday' => $registeredYesterdayCount,
                    'registeredYesterdayUpToFourDaysAgo' => $registeredYesterdayUpToFourDaysAgoCount,
                    'registered5UpTo9DaysAgo' => $registered5UpTo9DaysAgoCount,
                    'registered10UpTo19DaysAgo' => $registered10UpTo19DaysAgoCount,
                    'registered20UpTo29DaysAgo' => $registered20UpTo29DaysAgoCount,
                    'registered30UpTo39DaysAgo' => $registered30UpTo39DaysAgoCount,
                ]
            ]
        );
    }

    public function send(Request $request)
    {
        try {
            $alerts[] = [
                'type' => 'success',
                'message' => 'The process has been sent to the queue. Check back in a few seconds to see if the sending of the mass message is entered in the list of past messages.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'There was as problem. ' . $exception->getMessage()
            ];
        }

        Artisan::queue(
            'mass-message:send',
            [
                'body' => $request->get('body'),
                'limitation' => $request->get('limit_message')
            ],
        )
            ->onQueue('general');

        return redirect()->back()->with('alerts', $alerts);

//        ini_set('max_execution_time', 400);
//
//        $messageBody = $request->get('body');
//        $limitMessage = $request->get('limit_message');
//
//        $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();
//
//        $startOfToday = Carbon::now('Europe/Amsterdam')->startOfDay()->setTimezone('UTC');
//        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');
//        $startOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->startOfDay()->setTimezone('UTC');
//        $endOfYesterday = Carbon::now('Europe/Amsterdam')->subDays(1)->endOfDay()->setTimezone('UTC');
//        $startOfFourDaysAgo = Carbon::now('Europe/Amsterdam')->subDays(4)->startOfDay()->setTimezone('UTC');
//
//        $usersQuery = User::with(['meta'])
//        ->whereHas('roles', function ($query) {
//            $query->where('id', User::TYPE_PEASANT);
//        })
//        ->where('active', true)
//        ->whereHas('meta', function ($query) {
//            $query->where('gender', User::GENDER_MALE);
//            $query->where('looking_for_gender', User::GENDER_FEMALE);
//        });
//
//        if ($limitMessage === 'unlimited') {
//            // temporary solution
//            $usersQuery->where('exclude_from_mass_messages', '=', false);
//        }
//
//        if ($limitMessage === 'limited_with_pic') {
//            $usersQuery->where('exclude_from_mass_messages', '=', false);
//
//            $usersQuery->where(function ($query) {
//                $query->whereHas('meta', function ($query) {
//                    $query->where('dob', '!=', null);
//                    $query->orWhere('city', '!=', null);
//                })
//                    ->orWhereHas('images');
//            });
//        } else if ($limitMessage === 'limited_no_pic') {
//            $usersQuery->where('exclude_from_mass_messages', '=', false);
//
//            $usersQuery
//                ->whereDoesntHave('images')
//                ->where(function ($query) {
//                    $query->whereHas('meta', function ($query) {
//                        $query->where('dob', '=', null);
//                        $query->where('city', '=', null);
//                        $query->where('eye_color', '=', null);
//                        $query->where('hair_color', '=', null);
//                    });
//                });
//        } else if ($limitMessage === 'limited_have_payed') {
//
//            // temporary solution
//            $usersQuery->where('exclude_from_mass_messages', '=', false);
//
//            $usersQuery
//                ->whereHas('payments', function ($query) {
//                    $query->where('status', Payment::STATUS_COMPLETED);
//                });
//        } else if ($limitMessage === 'limited_have_payed_and_no_images') {
//
//            // temporary solution
//            $usersQuery->where('exclude_from_mass_messages', '=', false);
//
//            $usersQuery
//                ->whereHas('payments', function ($query) {
//                    $query->where('status', Payment::STATUS_COMPLETED);
//                })
//                ->whereDoesntHave('images');
//        } else if ($limitMessage === 'limited_today') {
//            $usersQuery
//                ->whereBetween('created_at', [$startOfToday, $endOfToday]);
//        } else if ($limitMessage === 'limited_yesterday') {
//            $usersQuery
//                ->whereBetween('created_at', [$startOfYesterday, $endOfYesterday]);
//        } else if ($limitMessage === 'limited_yesterday_up_to_four_days_ago') {
//            $usersQuery
//                ->whereBetween('created_at', [$startOfFourDaysAgo, $endOfYesterday]);
//        }
//
//        $users = $usersQuery->get();
//
//        $emailDelay = 0;
//        $loopCount = 0;
//        $errorsCount = 0;
//        $unmailableCount = 0;
////        try {
//            $alerts[] = [
//                'type' => 'success',
//                'message' => 'The process has been sent to the queue. Check back in a few seconds to see if the sending of the mass message is entered in the list of past messages.'
//            ];
//        } catch (\Exception $exception) {
//            $alerts[] = [
//                'type' => 'error',
//                'message' => 'There was as problem. ' . $exception->getMessage()
//            ];
//        }
//
//        Artisan::queue(
//            'mass-message:send',
//            [
//                'body' => $request->get('body'),
//                'limitation' => $request->get('limit_message')
//            ],
//        )
//        ->onQueue('general');
//
//        return redirect()->back()->with('alerts', $alerts);
//        /** @var User $user */
//        foreach ($users as $user) {
//            if ($loopCount % 5 === 0) {
//                $emailDelay++;
//            }
//
//            try {
//                \DB::beginTransaction();
//
//                $bot = User::where('active', true)
//                    ->whereHas('meta', function ($query) use ($user) {
//                        $query->where('looking_for_gender', $user->meta->gender);
//                        $query->where('gender', $user->meta->looking_for_gender);
//                    })
//                    ->whereHas('roles', function ($query) {
//                        $query->where('id', User::TYPE_BOT);
//                    })
//                    ->whereDoesntHave('conversationsAsUserA', function ($query) use ($user) {
//                        $query->where('user_b_id', $user->getId());
//                    })
//                    ->whereDoesntHave('conversationsAsUserB', function ($query) use ($user) {
//                        $query->where('user_a_id', $user->getId());
//                    })
//                    ->orderBy(\DB::raw('RAND()'))
//                    ->first();
//
//                if (!($bot instanceof User)) {
//                    continue;
//                }
//
//                $conversation = new Conversation([
//                    'user_a_id' => $bot->getId(),
//                    'user_b_id' => $user->getId()
//                ]);
//
//                $conversation->setNewActivityForUserB(true);
//                $conversation->setUpdatedAt(Carbon::now());
//                $conversation->save();
//
//                $messageInstance = new ConversationMessage([
//                    'conversation_id' => $conversation->getId(),
//                    'type' => 'generic',
//                    'sender_id' => $bot->getId(),
//                    'recipient_id' => $user->getId(),
//                    'body' => $messageBody,
//                    'has_attachment' => false,
//                    'operator_id' => null,
//                    'operator_message_type' => null
//                ]);
//
//                $messageInstance->save();
//
////                $recipientPartnerIds = OpenConversationPartner::where('user_id', $user->getId())
////                    ->get()
////                    ->pluck('partner_id')
////                    ->toArray();
////
////                $recipientOpenConversationPartnersCount = count($recipientPartnerIds);
////
////                if (!in_array($bot->getId(), $recipientPartnerIds) && $recipientOpenConversationPartnersCount < 2) {
////                    $user->addOpenConversationPartner($bot, 1);
////                }
//
//                $recipientEmailTypeIds = $user->emailTypes->pluck('id')->toArray();
//
//                $recipientHasMessageNotificationsEnabled = in_array(
//                    EmailType::MESSAGE_RECEIVED,
//                    $recipientEmailTypeIds
//                );
//
//                if (
//                    $recipientHasMessageNotificationsEnabled &&
//                    !in_array($user->getId(), $onlineUserIds)
//                ) {
//                    if ($user->isMailable) {
//                        if (config('app.env') === 'production') {
//                            $messageReceivedEmail =
//                                (new MessageReceived(
//                                    $bot,
//                                    $user,
//                                    $messageBody,
//                                    false
//                                ))
//                                ->delay($emailDelay)
//                                ->onQueue('emails');
//
//                            Mail::to($user)
//                                ->queue($messageReceivedEmail);
//                        }
//
//                        $user->emailTypeInstances()->attach(EmailType::MESSAGE_RECEIVED, [
//                            'email' => $user->getEmail(),
//                            'email_type_id' => EmailType::MESSAGE_RECEIVED,
//                            'actor_id' => $bot->getId()
//                        ]);
//                    } else {
//                        $unmailableCount++;
//                    }
//                }
//
//                $this->userManager->storeProfileView(
//                    $bot,
//                    $user,
//                    UserView::TYPE_BOT_MESSAGE
//                );
//
//                \DB::commit();
//            } catch (\Exception $exception) {
//                $errorsCount++;
//
//                \DB::rollBack();
//
//                \Log::info(__CLASS__ . ' - ' . $exception->getMessage());
//            }
//
//            $loopCount++;
//        }
//
//        if ($errorsCount) {
//            $alerts[] = [
//                'type' => 'warning',
//                'message' => ($users->count() - $errorsCount) . ' messages were sent and ' . $errorsCount . ' messages were not sent due to errors, ' . $unmailableCount . ' not sent due to the emails being un-mailable. ' . $unmailableCount . ' emails were not sent to un-mailable accounts.'
//            ];
//        } else {
//            $alerts[] = [
//                'type' => 'success',
//                'message' => $users->count() . ' messages were sent successfully. ' . $unmailableCount . ' emails were not sent to un-mailable accounts'
//            ];
//        }
//
//        $pastMassMessageInstance = new PastMassMessage();
//        $pastMassMessageInstance->setBody($messageBody);
//        $pastMassMessageInstance->setUserCount($users->count() - $errorsCount);
//        $pastMassMessageInstance->save();
//
//        return redirect()->back()->with('alerts', $alerts);
    }
}
