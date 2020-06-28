<?php

namespace App\Console\Commands;

use App\Conversation;
use App\ConversationMessage;
use App\EmailType;
use App\Mail\MessageReceived;
use App\Managers\UserManager;
use App\PastMassMessage;
use App\User;
use App\UserMeta;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Kim\Activity\Activity;

class SendMassMessage extends Command
{
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
        $messageBody = $this->argument('body');
        $limitMessage = $this->argument('limitation');

        $onlineUserIds = Activity::users(5)->pluck('user_id')->toArray();

        $usersQuery = User::with(['meta'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            });

        if ($limitMessage === 'limited_with_pic') {
            $usersQuery->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '!=', null);
                    $query->orWhere('city', '!=',  null);
                })
                    ->orWhereHas('images');
            });
        } else if ($limitMessage === 'limited_no_pic') {
            $usersQuery
                ->whereDoesntHave('images')
                ->where(function ($query) {
                    $query->whereHas('meta', function ($query) {
                        $query->where('dob', '=', null);
                        $query->where('city', '=',  null);
                        $query->where('eye_color', '=',  null);
                        $query->where('hair_color', '=',  null);
                    });
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
                    !in_array($user->getId(), $onlineUserIds) &&
                    $user->isMailable
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

        $pastMassMessageInstance = new PastMassMessage();
        $pastMassMessageInstance->setBody($messageBody);
        $pastMassMessageInstance->setUserCount($users->count() - $errorsCount);
        $pastMassMessageInstance->save();

        if ($errorsCount) {
            \Log::debug(($users->count() - $errorsCount) . ' messages were sent and ' . $errorsCount . ' messages were not sent');

        } else {
            \Log::debug($users->count() . ' messages were sent successfully');
        }
    }
}
