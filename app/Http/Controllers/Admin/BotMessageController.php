<?php

namespace App\Http\Controllers\Admin;

use App\BotMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BotMessages\BotMessageCreateRequest;
use App\Http\Requests\Admin\BotMessages\BotMessageUpdateRequest;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;

class BotMessageController extends Controller
{
    public function __construct(
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $botMessages = BotMessage::with(['bot'])->orderBy('usage_type', 'asc')->paginate(40);

        return view(
            'admin.bot-messages.overview',
            [
                'title' => 'Bot Messages Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Bot Messages',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'botMessages' => $botMessages
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ofBotId(int $botId)
    {
        $botMessages = BotMessage::orderBy('created_at', 'desc')
            ->where('bot_id', $botId)
            ->paginate(40);

        /** @var User $bot */
        $bot = User::find($botId);

        return view(
            'admin.bot-messages.overview',
            [
                'title' => 'Bot Messages of bot ID: ' . $botId . ' - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Bot Messages of ' . $bot->getUsername() . '(ID: ' . $bot->getId() . ')',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'botMessages' => $botMessages
            ]
        );
    }

    /**
     * @param int $botMessageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $botMessageId)
    {
        try {
            BotMessage::destroy($botMessageId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The bot message was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The bot message was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $bots = User::withCount([
                'botMessages'
            ])->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->get()
            ->sortByDesc(function ($user) {
                return $user->botMessages->count();
            });

        return view(
            'admin.bot-messages.create',
            [
                'title' => 'Create bot message - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Bot Messages',
                'headingSmall' => 'Create',
                'bots' => $bots
            ]
        );
    }

    /**
     * @param int $botMessageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $botMessageId)
    {
        $bots = User::withCount([
            'botMessages'
        ])->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->get()
            ->sortByDesc(function ($user) {
                return $user->botMessages->count();
            });

        return view(
            'admin.bot-messages.edit',
            [
                'title' => 'Edit bot message - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Bot Messages',
                'headingSmall' => 'Edit',
                'botMessage' => BotMessage::find($botMessageId),
                'bots' => $bots
            ]
        );
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(BotMessageCreateRequest $request)
    {
        try {
            BotMessage::create([
                'body' => $request->get('body'),
                'bot_id' => $request->get('bot_id'),
                'status' => $request->get('status'),
                'usage_type' => $request->get('usage_type')
            ]);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The bot message was created.'
            ];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            $alerts[] = [
                'type' => 'error',
                'message' => 'The bot message was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param int $botMessageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BotMessageUpdateRequest $request, int $botMessageId)
    {
        try {
            /** @var BotMessage $botMessage */
            $botMessage = BotMessage::findOrFail($botMessageId);
            $botMessage->setBody($request->get('body'));
            $botMessage->setStatus($request->get('status'));
            $botMessage->setUsageType($request->get('usage_type'));
            $botMessage->setBotId($request->get('bot_id'));
            $botMessage->save();

            $alerts[] = [
                'type' => 'success',
                'message' => 'The bot message was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The bot message was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
