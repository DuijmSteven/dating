<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Bots\BotCreateRequest;
use App\Http\Requests\Backend\Bots\BotUpdateRequest;
use App\Managers\BotManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bots = User::with(['meta', 'roles'])->whereHas('roles', function ($query) {
            $query->where('name', 'bot');
        })->paginate(10);

        return view(
            'backend.bots.index',
            [
                'title' => 'Bot Overview - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Bot',
                'headingSmall' => 'Overview',
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
            'backend.bots.create',
            [
                'title' => 'Create Bot - ' . \MetaConstants::SITE_NAME,
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

        $this->botManager->createBot($botData);

        return redirect()->back();
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
        $bot = User::findOrFail($request->route('id'));

        return view(
            'backend.bots.edit',
            [
                'title' => 'Edit Bot - '. $bot['username'] . '(ID: '. $bot['id'] .') - ' . \MetaConstants::SITE_NAME,
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

        $this->botManager->updateBot($botData, $botUpdateRequest->route('id'));

        return redirect()->back();
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