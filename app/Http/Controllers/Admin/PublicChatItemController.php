<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePublicChatItemRequest;
use App\PublicChatItem;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class PublicChatItemController
 * @package App\Http\Controllers\Admin
 */
class PublicChatItemController extends Controller
{
    public function __construct(
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $publicChatItems = PublicChatItem::with([
            'sender',
            'operator'
        ])
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.public-chat-items.overview',
            [
                'title' => 'Public chat items Overview - ' . \config('app.name'),
                'headingLarge' => 'Public chat items',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'publicChatItems' => $publicChatItems
            ]
        );
    }

    /**
     * @param $peasantId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofPeasantId($peasantId)
    {
        $publicChatItems = PublicChatItem::with([
            'sender',
            'operator'
        ])
            ->where('sender_id', $peasantId)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $peasant */
        $peasant = User::find($peasantId);

        return view(
            'admin.public-chat-items.overview',
            [
                'title' => 'Public chat items of peasant ID: ' . $peasant->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Public chat items of peasant',
                'headingSmall' => $peasant->getUsername() . ' - (ID: ' . $peasant->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'publicChatItems' => $publicChatItems
            ]
        );
    }

    public function ofBotId($botId)
    {
        $publicChatItems = PublicChatItem::with([
            'sender',
            'operator'
        ])
            ->where('sender_id', $botId)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $bot */
        $bot = User::find($botId);

        return view(
            'admin.public-chat-items.overview',
            [
                'title' => 'Public chat items of bot ID: ' . $bot->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Public chat items of bot',
                'headingSmall' => $bot->getUsername() . ' - (ID: ' . $bot->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'publicChatItems' => $publicChatItems
            ]
        );
    }

    public function ofOperatorId($operator)
    {
        $publicChatItems = PublicChatItem::with([
            'sender',
            'operator'
        ])
            ->where('operator_id', $operator)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $bot */
        $bot = User::find($operator);

        return view(
            'admin.public-chat-items.overview',
            [
                'title' => 'Public chat items of operator ID: ' . $bot->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Public chat items of operator',
                'headingSmall' => $bot->getUsername() . ' - (ID: ' . $bot->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'publicChatItems' => $publicChatItems
            ]
        );
    }


    /**
     * @param int $chatItemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $chatItemId)
    {
        try {
            PublicChatItem::where('id', $chatItemId)->delete();

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was deleted.'
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            $alerts[] = [
                'type' => 'error',
                'message' => 'The message was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    public function showSendAsBot()
    {
        $onlineIds = $this->userActivityService->getOnlineUserIds(
            $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
        );

        $onlineBotIds = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereIn('id', $onlineIds)
            ->get()->pluck('id')->toArray();

        $botsQueryBuilder = User::with(['meta', 'roles', 'profileImage', 'publicChatMessages', 'uniqueViews'])
            ->withCount(['publicChatMessages'])
            ->whereDoesntHave('conversationsAsUserA', function ($query) {
                $query->has('messages', '>', 20);
                $query->whereHas('messages', function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(6));
                });
            })
            ->whereDoesntHave('conversationsAsUserB', function ($query) {
                $query->has('messages', '>', 20);
                $query->whereHas('messages', function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->subDays(6));
                });
            })
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            });

        $publicChatMessagesQueryBuilder = PublicChatItem
            ::with(['sender', 'sender.roles'])
//            ->where(function ($query) use ($forGender, $forLookingForGender) {
//                $query->whereHas('sender.meta', function ($query) use ($forGender, $forLookingForGender) {
//                    $query->where('gender', $forLookingForGender);
//                    $query->where('looking_for_gender', $forGender);
//                })
//                    ->orWhereHas('sender', function ($query) {
//                        $query->where('id', auth('api')->user()->getId());
//                    });
//            })
            ->whereHas('sender', function ($query) {
                $query->where('active', true);
            })
            ->where('published_at', '<=', Carbon::now())
            ->orderBy('published_at', 'desc');

        $publicChatMessagesQueryBuilder->skip(0);
        $publicChatMessagesQueryBuilder->take(100);

        return view(
            'admin.public-chat-items.send-as-bot',
            [
                'title' => 'Public chat as Bot- ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'Post in public chat as bot',
                'carbonNow' => Carbon::now(),
                'bots' => $botsQueryBuilder->get()->sortByDesc(function ($bot) {
                    return $bot->uniqueViews->count();
                }),
                'onlineBotIds' => $onlineBotIds,
                'publicChatItems' =>  $publicChatMessagesQueryBuilder->get()
            ]
        );
    }

    public function sendAsBot(CreatePublicChatItemRequest $createPublicChatItemRequest) {
        try {
            if ($createPublicChatItemRequest->get('published_at')) {
                $publishedAtInCurrentTimezone = Carbon::parse(
                    $createPublicChatItemRequest->get('published_at'),
                    'Europe/Amsterdam'
                );

                $publishedAtInUtc = $publishedAtInCurrentTimezone->setTimezone('UTC');
            } else {
                $publishedAtInUtc = Carbon::now('UTC');
            }

            DB::beginTransaction();

            $messageData = $createPublicChatItemRequest->all();

            $publicChatItem = new PublicChatItem();
            $publicChatItem->setBody($messageData['text']);
            $publicChatItem->setSenderId($messageData['sender_id']);
            $publicChatItem->setType($messageData['type']);
            $publicChatItem->setPublishedAt($publishedAtInUtc);
            $publicChatItem->save();

            $activity = new Activity;
            $activity->id = bcrypt((int) time() . $messageData['sender_id']);
            $activity->last_activity = time();
            $activity->user_id = $messageData['sender_id'];
            $activity->save();

            DB::commit();

            $alerts[] = [
                'type' => 'success',
                'message' => 'The message was posted.'
            ];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            DB::rollBack();

            $alerts[] = [
                'type' => 'error',
                'message' => 'The message was not posted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
