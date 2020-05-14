<?php

namespace App\Http\Controllers\Admin;

use App\Conversation;
use App\ConversationMessage;
use App\EmailType;
use App\Http\Controllers\Controller;
use App\Mail\MessageReceived;
use App\Managers\UserManager;
use App\PastMassMessage;
use App\User;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        /** @var Collection $bots */
        $queryBuilder = User::with(
            User::COMMON_RELATIONS
        )
            ->withCount(
                User::EDITOR_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_EDITOR)
                    ->orWhere('id', User::TYPE_ADMIN);
            });

        $editors = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.mass-messages.new',
            [
                'title' => 'New Mass Message - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Mass Messages',
                'headingSmall' => 'New Mass Message',
                'pastMassMessages' => PastMassMessage::orderBy('created_at', 'desc')->get(),
                'carbonNow' => Carbon::now()
            ]
        );
    }

    public function send(Request $request)
    {
        $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();

        $usersQuery = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            });

        if ($request->get('limited_to_filled_profiles') && $request->get('limited_to_filled_profiles') === '1') {
            $usersQuery->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '!=', null);
                    $query->orWhere('city', '!=',  null);
                })
                ->orWhereHas('images');
            });

        }

        $users = $usersQuery->get();

        $errorsCount = 0;

        /** @var User $user */
        foreach ($users as $user) {
            try {
                \DB::beginTransaction();

                $bot = User::with(['emailTypes'])
                    ->where('active', true)
                    ->whereHas('meta', function ($query) use ($user) {
                        $query->where('looking_for_gender', $user->meta->gender);
                        $query->where('gender', $user->meta->looking_for_gender);
                    })
                    ->whereHas('roles', function ($query) {
                        $query->where('id', User::TYPE_BOT);
                    })
                    ->whereDoesntHave('conversationsAsUserA', function ($query) use ($user) {
                        $query->where('user_b_id', $user->getId());
                    })
                    ->whereDoesntHave('conversationsAsUserB', function ($query) use ($user) {
                        $query->where('user_a_id', $user->getId());
                    })
                    ->orderBy(\DB::raw('RAND()'))
                    ->first();

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

                $messageBody = $request->get('body');
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
                'message' => $users->count() . ' messages were sent and ' . $errorsCount . ' messages were not sent'
            ];
        } else {
            $alerts[] = [
                'type' => 'success',
                'message' => $users->count() . ' messages were sent successfully'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
