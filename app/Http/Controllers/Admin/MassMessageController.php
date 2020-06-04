<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\SendMassMessage;
use App\Conversation;
use App\ConversationMessage;
use App\EmailType;
use App\Http\Controllers\Controller;
use App\Mail\MessageReceived;
use App\Managers\UserManager;
use App\PastMassMessage;
use App\Payment;
use App\User;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Kim\Activity\Activity;

class MassMessageController extends Controller
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        parent::__construct();
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

        $unlimitedCount = $unlimitedUsersQuery->count();

        $withoutPicUsersQuery = clone $unlimitedUsersQuery;
        $withPicUsersQuery = clone $unlimitedUsersQuery;
        $havePayedUsersQuery = clone $unlimitedUsersQuery;
        $havePayedAndDontHaveImagesUsersQuery = clone $unlimitedUsersQuery;

        $withPicUsersQuery->where(function ($query) {
            $query->whereHas('meta', function ($query) {
                $query->where('dob', '!=', null);
                $query->orWhere('city', '!=',  null);
            })
                ->orWhereHas('images');
        });

        $withPicCount = $withPicUsersQuery->count();

        $withoutPicUsersQuery
            ->whereDoesntHave('images')
            ->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '=', null);
                    $query->where('city', '=',  null);
                    $query->where('eye_color', '=',  null);
                    $query->where('hair_color', '=',  null);
                });
            });

        $withoutPicCount = $withoutPicUsersQuery->count();

        $havePayedUsersQuery->whereHas('payments', function ($query) {
            $query->where('status', Payment::STATUS_COMPLETED);
        });

        $havePayedCount = $havePayedUsersQuery->count();

        $havePayedAndDontHaveImagesUsersQuery->whereHas('payments', function ($query) {
            $query->where('status', Payment::STATUS_COMPLETED);
        })
        ->whereDoesntHave('images');

        $havePayedAndDontHaveImagesCount = $havePayedAndDontHaveImagesUsersQuery->count();

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
                ]
            ]
        );
    }

    public function send(Request $request)
    {
        ini_set('max_execution_time', 400);

        $messageBody = $request->get('body');
        $limitMessage = $request->get('limit_message');

        $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();

        $straightMaleActiveUsersQuery = User::with(['conversationsAsUserA', 'conversationsAsUserB'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            });

        if ($limitMessage === 'limited_with_pic') {
            $straightMaleActiveUsersQuery->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '!=', null);
                    $query->orWhere('city', '!=',  null);
                })
                    ->orWhereHas('images');
            });
        } else if ($limitMessage === 'limited_no_pic') {
            $straightMaleActiveUsersQuery
                ->whereDoesntHave('images')
                ->where(function ($query) {
                    $query->whereHas('meta', function ($query) {
                        $query->where('dob', '=', null);
                        $query->where('city', '=',  null);
                        $query->where('eye_color', '=',  null);
                        $query->where('hair_color', '=',  null);
                    });
                });
        } else if ($limitMessage === 'limited_have_payed') {
            $straightMaleActiveUsersQuery
                ->whereHas('payments', function ($query) {
                    $query->where('status', Payment::STATUS_COMPLETED);
                });
        } else if ($limitMessage === 'limited_have_payed_and_no_images') {
            $straightMaleActiveUsersQuery
                ->whereHas('payments', function ($query) {
                    $query->where('status', Payment::STATUS_COMPLETED);
                })
                ->whereDoesntHave('images');
        }

        $users = $straightMaleActiveUsersQuery->get();

        $errorsCount = 0;

        $bots = User::with(['conversationsAsUserA', 'conversationsAsUserB'])
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('looking_for_gender', User::GENDER_MALE);
                $query->where('gender',  User::GENDER_FEMALE);
            })
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->get();

        /** @var User $user */
        foreach ($users as $user) {
            try {
                \DB::beginTransaction();
                $bot = $bots->filter(function ($bot, $key) use ($user) {
                    return !$bot->conversationsAsUserA->contains(function ($conversation, $key) use ($user) {
                        return $conversation->user_b_id === $user->getId();
                    })
                        &&
                    !$bot->conversationsAsUserB->contains(function ($conversation, $key) use ($user) {
                        return $conversation->user_a_id === $user->getId();
                    });
                })
                ->random();

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

                $user->addOpenConversationPartner($bot, 1);

                $recipientEmailTypeIds = $user->emailTypes->pluck('id')->toArray();

                $recipientHasMessageNotificationsEnabled = in_array(
                    EmailType::MESSAGE_RECEIVED,
                    $recipientEmailTypeIds
                );

                if (
                    $recipientHasMessageNotificationsEnabled &&
                    !in_array($user->getId(), $onlineUserIds)
                ) {
                    if (config('app.env') === 'production') {
                        $messageReceivedEmail = (new MessageReceived(
                            $bot,
                            $user,
                            $messageBody,
                            false
                        ))->onQueue('emails');

                        Mail::to($user)
                            ->queue($messageReceivedEmail);
                    }

                    $user->emailTypeInstances()->attach(EmailType::MESSAGE_RECEIVED, [
                        'email' => $user->getEmail(),
                        'email_type_id' => EmailType::MESSAGE_RECEIVED,
                        'actor_id' => $bot->getId()
                    ]);
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
        }

        if ($errorsCount) {
            $alerts[] = [
                'type' => 'warning',
                'message' => ($users->count() - $errorsCount) . ' messages were sent and ' . $errorsCount . ' messages were not sent'
            ];
        } else {
            $alerts[] = [
                'type' => 'success',
                'message' => $users->count() . ' messages were sent successfully'
            ];
        }

        $pastMassMessageInstance = new PastMassMessage();
        $pastMassMessageInstance->setBody($messageBody);
        $pastMassMessageInstance->setUserCount($users->count() - $errorsCount);
        $pastMassMessageInstance->save();

        return redirect()->back()->with('alerts', $alerts);
//        try {
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
    }
}
