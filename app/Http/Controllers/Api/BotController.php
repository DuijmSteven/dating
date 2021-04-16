<?php

namespace App\Http\Controllers\Api;

use App\Http\Validators\BotCreate;
use App\Http\Validators\BotUpdate;
use App\Managers\BotManager;
use App\Managers\UserManager;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class BotController
 * @package App\Http\Controllers
 */
class BotController
{
    /**
     * @var BotManager
     */
    private BotManager $botManager;
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * BotController constructor.
     * @param BotManager $botManager
     */
    public function __construct(
        BotManager $botManager,
        UserManager $userManager
    ) {
        $this->botManager = $botManager;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, int $botId)
    {
        $validator = Validator::make($request->all(), BotUpdate::rules($botId, $request->files));

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        /** @var User $requestingUser */
        $requestingUser = $request->user();

        /** @var User $bot */
        $bot = User::with('createdByOperator')->find($botId);

        if (
            !$requestingUser->isAdmin() &&
            $bot->createdByOperator->getId() !== $requestingUser->getId()
        ) {
            return response()->json('You cannot edit profiles that you have not created', 401);
        }

        $botData = $request->all();

        try {
            $this->botManager->updateBot($botData, $botId);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), BotCreate::rules($request->files));

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        $botData = $request->all();
        $botData['city'] = trim(strtolower($botData['city']));

        \Log::info($request->user()->getId());

        $botData['user']['created_by_id'] = $request->user()->getId();

        try {
            $this->botManager->createBot($botData);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
