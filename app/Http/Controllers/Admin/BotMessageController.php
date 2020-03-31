<?php

namespace App\Http\Controllers\Admin;

use App\BotMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BotMessages\BotMessageCreateRequest;
use App\Http\Requests\Admin\BotMessages\BotMessageUpdateRequest;
use Carbon\Carbon;

class BotMessageController extends Controller
{
    public function __construct(
    ) {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $botMessages = BotMessage::orderBy('created_at', 'desc')->paginate(40);

        return view(
            'admin.bot-messages.overview',
            [
                'title' => 'Bot Messages Overview - ' . \config('app.name'),
                'headingLarge' => 'Bot Messages',
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
        return view(
            'admin.bot-messages.create',
            [
                'title' => 'Create bot message - ' . \config('app.name'),
                'headingLarge' => 'Bot Messages',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param int $botMessageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $botMessageId)
    {
        return view(
            'admin.bot-messages.edit',
            [
                'title' => 'Edit bot message - ' . \config('app.name'),
                'headingLarge' => 'Bot Messages',
                'headingSmall' => 'Edit',
                'botMessage' => BotMessage::find($botMessageId)
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
                'status' => $request->get('status')
            ]);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The article was created.'
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
