<?php

namespace App\Http\Controllers\Admin;

use App\ConversationMessage;
use App\Http\Controllers\Controller;
use App\PublicChatItem;
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
    ) {
        parent::__construct();
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
}
