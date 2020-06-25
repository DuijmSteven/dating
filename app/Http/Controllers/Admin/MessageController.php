<?php

namespace App\Http\Controllers\Admin;

use App\ConversationMessage;
use App\Http\Controllers\Controller;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class MessageController
 * @package App\Http\Controllers\Admin
 */
class MessageController extends Controller
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
        $messages = ConversationMessage::with([
            'sender',
            'recipient'
        ])
            ->with(['conversation' => function ($query) {
                $query->withTrashed();
            }])
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.messages.overview',
            [
                'title' => 'Messages Overview - ' . \config('app.name'),
                'headingLarge' => 'Messages',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'messages' => $messages
            ]
        );
    }

    /**
     * @param $peasantId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofPeasantId($peasantId)
    {
        $messages = ConversationMessage::with([
            'sender',
            'recipient'
        ])
            ->with(['conversation' => function ($query) {
                $query->withTrashed();
            }])
            ->where('sender_id', $peasantId)
            ->orWhere('recipient_id', $peasantId)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $peasant */
        $peasant = User::find($peasantId);

        return view(
            'admin.messages.overview',
            [
                'title' => 'Messages of peasant ID: ' . $peasant->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Messages of peasant',
                'headingSmall' => $peasant->getUsername() . ' - (ID: ' . $peasant->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'messages' => $messages
            ]
        );
    }

    public function ofBotId($botId)
    {
        $messages = ConversationMessage::with([
            'sender',
            'recipient'
        ])
            ->with(['conversation' => function ($query) {
                $query->withTrashed();
            }])
            ->where('sender_id', $botId)
            ->orWhere('recipient_id', $botId)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $bot */
        $bot = User::find($botId);

        return view(
            'admin.messages.overview',
            [
                'title' => 'Messages of bot ID: ' . $bot->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Messages of bot',
                'headingSmall' => $bot->getUsername() . ' - (ID: ' . $bot->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'messages' => $messages
            ]
        );
    }

    public function ofOperatorId($operator)
    {
        $messages = ConversationMessage::with([
            'sender',
            'recipient'
        ])
            ->with(['conversation' => function ($query) {
                $query->withTrashed();
            }])
            ->where('operator_id', $operator)
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        /** @var User $bot */
        $bot = User::find($operator);

        return view(
            'admin.messages.overview',
            [
                'title' => 'Messages of operator ID: ' . $bot->getId() . ' - ' . \config('app.name'),
                'headingLarge' => 'Messages of operator',
                'headingSmall' => $bot->getUsername() . ' - (ID: ' . $bot->getId() . ')',
                'carbonNow' => Carbon::now('Europe/Amsterdam'),
                'messages' => $messages
            ]
        );
    }


    /**
     * @param int $messageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $messageId)
    {
        try {
            ConversationMessage::where('id', $messageId)->delete();

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
