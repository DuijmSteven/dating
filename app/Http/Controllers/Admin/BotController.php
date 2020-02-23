<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Bots\BotCreateRequest;
use App\Http\Requests\Admin\Bots\BotUpdateRequest;
use App\Managers\BotManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Kim\Activity\Activity;

/**
 * Class BotController
 * @package App\Http\Controllers\Admin
 */
class BotController extends Controller
{
    /** @var BotManager  */
    private $botManager;

    /**
     * BotController constructor.
     * @param BotManager $botManager
     */
    public function __construct(BotManager $botManager)
    {
        $this->botManager = $botManager;
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var Collection $bots */
        $bots = User::with(['meta', 'roles', 'profileImage', 'images'])->whereHas('roles', function ($query) {
            $query->where('name', 'bot');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view(
            'admin.bots.overview',
            [
                'title' => 'Bot Overview - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Bot',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'bots' => $bots
            ]
        );
    }

    public function showOnline()
    {
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $bots = User::with(['meta', 'roles', 'profileImage'])->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->get();

        return view(
            'admin.bots.online',
            [
                'title' => 'Online bots - ' . \config('app.name'),
                'headingLarge' => 'Bots',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'bots' => $bots
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $bot = User::with(['meta', 'profileImage', 'nonProfileImages'])->findOrFail($request->route('id'));

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
