<?php

namespace App\Http\Controllers\Admin;

use App\Conversation;
use App\ConversationMessage;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Kim\Activity\Activity;

class MassMessageController extends Controller
{
    public function __construct(
    ) {
        parent::__construct();
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
                'carbonNow' => Carbon::now()
            ]
        );
    }

    public function send(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
        ->whereHas('meta', function ($query) {
            $query->where('gender', User::GENDER_MALE);
            $query->where('looking_for_gender', User::GENDER_FEMALE);
        })
        ->where('active', true)
        ->get();

        /** @var User $user */
        foreach ($users as $user) {
            try {
                \DB::beginTransaction();

                $bot = User::where('active', true)
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
                    'body' => $request->get('body'),
                    'has_attachment' => false,
                    'operator_id' => null,
                    'operator_message_type' => null
                ]);

                $messageInstance->save();

                \DB::commit();

            } catch (\Exception $exception) {
                \DB::rollBack();

                \Log::info(__CLASS__ . ' - ' . $exception->getMessage());
                throw $exception;
            }
        }
    }
}
