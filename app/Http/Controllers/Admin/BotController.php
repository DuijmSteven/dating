<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Bots\BotCreateRequest;
use App\Http\Requests\Admin\Bots\BotUpdateRequest;
use App\Managers\BotManager;
use App\Managers\UserManager;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

/**
 * Class BotController
 * @package App\Http\Controllers\Admin
 */
class BotController extends Controller
{
    /** @var BotManager  */
    private $botManager;

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
        UserManager $userManager,
        UserActivityService $userActivityService
    ) {
        $this->botManager = $botManager;
        parent::__construct($userActivityService);
        $this->userManager = $userManager;
    }

    /**
     * @param int $editorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var Collection $bots */
        $queryBuilder = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::BOT_RELATIONS
            ))
        )
            ->withCount(
                User::BOT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            });

        $bots = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $editBotRoute = '';

        return view(
            'admin.bots.overview',
            [
                'title' => 'Bot Overview - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Bot',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'bots' => $bots,
                'editBotRoute' =>'admin.bots.edit.get'
            ]
        );
    }

    public function showOnMap()
    {
        \Mapper::renderJavascript();
        \Mapper::map(52.125927, 5.451147, ['zoom' => 8, 'markers' => ['title' => 'My Location', 'animation' => 'DROP']]);

        $bots = User::with('meta')->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->get();

        foreach ($bots as $bot) {
            if ($bot->meta->lat && $bot->meta->lng) {
                \Mapper::marker($bot->meta->lat,  $bot->meta->lng);
            }
        }

        return view('admin.bots.map',
            [
                'title' => 'Bots on Map - ' . \config('app.name'),
                'headingLarge' => 'Bots',
                'headingSmall' => 'On Map',
                'bots' => $bots
            ]
        );
    }

    public function messagePeasantWithBot(int $botId, bool $onlyOnlinePeasants = false)
    {
        $onlineIds = $this->userActivityService->getOnlineUserIds(
            $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
        );

        $onlinePeasantIds = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->whereIn('id', $onlineIds)
            ->get()->pluck('id')->toArray();

        $peasantsQueryBuilder = User::with('meta', 'roles', 'profileImage')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            });

        if ($onlyOnlinePeasants) {
            $peasantsQueryBuilder->whereIn('id', $onlinePeasantIds);
        }

        return view(
            'admin.bots.message-with-bot',
            [
                'title' => 'Message Peasant With Bot- ' . \config('app.name'),
                'headingLarge' => 'Bots',
                'headingSmall' => 'Message peasant with bot',
                'carbonNow' => Carbon::now(),
                'bot' => User::with('meta', 'profileImage')->find($botId),
                'peasants' => $peasantsQueryBuilder
                    ->get(),
                'onlinePeasantIds' => $onlinePeasantIds
            ]
        );
    }

    public function showOnline()
    {
        $onlineIds = $this->userActivityService->getOnlineUserIds(
            $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
        );

        $bots = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::BOT_RELATIONS
            ))
        )
            ->withCount(
                User::BOT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->paginate(20);

        return view(
            'admin.bots.overview',
            [
                'title' => 'Online bots - ' . \config('app.name'),
                'headingLarge' => 'Bots',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'bots' => $bots,
                'editBotRoute' =>'admin.bots.edit.get'
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(
            'admin.bots.create',
            [
                'title' => 'Create Bot - ' . \config('app.name'),
                'headingLarge' => 'Bot',
                'headingSmall' => 'Create',
                'carbonNow' => Carbon::now(),
            ]
        );
    }

    /**
     * @param BotCreateRequest $botCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(BotCreateRequest $botCreateRequest)
    {
        $botCreateRequest->formatInput();
        $botData = $botCreateRequest->all();
        $botData['city'] = strtolower($botData['city']);

        try {
            $this->botManager->createBot($botData);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The bot was created successfully'
            ];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            $alerts[] = [
                'type' => 'error',
                'message' => 'The bot was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(int $botId, Request $request)
    {
        $bot = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::BOT_RELATIONS
            ))
        )
            ->withCount(
                User::BOT_RELATION_COUNTS
            )
            ->findOrFail($botId);

        if ($this->authenticatedUser->isEditor()) {
            $authorizationProblems = 0;

            if (!in_array($bot->getId(), $this->authenticatedUser->createdBotIds)) {
                \Log::debug('Editor ' . ($this->authenticatedUser->getId() . ' attempted to edit bot ' . $bot->getId() . ', which he did not create'));

                $authorizationProblems++;
                $alerts[] = [
                    'type' => 'error',
                    'message' => 'You don\'t have permission to edit bots you have not created'
                ];
            }
            if ($bot->active) {
                \Log::debug('Editor ' . ($this->authenticatedUser->getId() . ' attempted to edit active bot ' . $bot->getId() . ', which he created'));

                $authorizationProblems++;
                $alerts[] = [
                    'type' => 'error',
                    'message' => 'You don\'t have permission to edit active bots'
                ];
            }

            if ($authorizationProblems) {
                return redirect()->route('editors.bots.created.overview')->with('alerts', $alerts);
            }
        }

        return view(
            'admin.bots.edit',
            [
                'title' => 'Edit Bot - '. $bot['username'] . '(ID: '. $bot['id'] .') - ' . \config('app.name'),
                'headingLarge' => 'Bot',
                'headingSmall' => 'Edit',
                'carbonNow' => Carbon::now(),
                'bot' => $bot
            ]
        );
    }

    /**
     * @param BotUpdateRequest $botUpdateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BotUpdateRequest $botUpdateRequest)
    {
        $botUpdateRequest->formatInput();
        $botData = $botUpdateRequest->all();

        try {
            $this->botManager->updateBot($botData, $botUpdateRequest->route('id'));

            $alerts[] = [
                'type' => 'success',
                'message' => 'The bot was updated successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The bot was not updated due to an exception.'
            ];
        }
        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->userManager->deleteUser($id);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The bot was deleted successfully'
            ];

            return redirect()->route('admin.bots.retrieve')->with('alerts', $alerts);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The bot was not deleted due to an exception.'
            ];

            return redirect()->route('admin.bots.retrieve')->with('alerts', $alerts);
        }
    }
}
