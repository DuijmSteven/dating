<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Peasants\PeasantCreateRequest;
use App\Http\Requests\Admin\Peasants\PeasantUpdateRequest;
use App\Managers\PeasantManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kim\Activity\Activity;

class PeasantController extends Controller
{
    /** @var PeasantManager  */
    private $peasantManager;

    /**
     * PeasantController constructor.
     * @param PeasantManager $peasantManager
     */
    public function __construct(PeasantManager $peasantManager)
    {
        $this->peasantManager = $peasantManager;
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peasants = User::with(['meta', 'account', 'roles', 'profileImage'])->whereHas('roles', function ($query) {
            $query->where('name', 'peasant');
        })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Peasant Overview - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'peasants' => $peasants
            ]
        );
    }

    public function showOnMap()
    {
        \Mapper::renderJavascript();
        \Mapper::map(52.125927, 5.451147, ['zoom' => 8, 'markers' => ['animation' => 'DROP']]);

        $peasants = User::with('meta')->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->get();

        foreach ($peasants as $peasant) {
            if ($peasant->meta->lat && $peasant->meta->lng) {
                \Mapper::marker($peasant->meta->lat,  $peasant->meta->lng);
            }
        }

        return view('admin.peasants.map',
            [
                'title' => 'Peasants on Map - ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'On Map',
                'peasants' => $peasants
            ]
        );
    }

    public function showOnline()
    {
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $peasants = User::with(['meta', 'account', 'roles', 'profileImage'])->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->whereIn('id', $onlineIds) 
            ->orderBy('id')
            ->get();

        return view(
            'admin.peasants.online',
            [
                'title' => 'Online peasants - ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'peasants' => $peasants
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
            'admin.peasants.create',
            [
                'title' => 'Create Peasant - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Create',
                'carbonNow' => Carbon::now(),
            ]
        );
    }

    /**
     * @param PeasantCreateRequest $peasantCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PeasantCreateRequest $peasantCreateRequest)
    {
        $peasantCreateRequest->formatInput();
        $peasantData = $peasantCreateRequest->all();
        $peasantData['city'] = strtolower($peasantData['city']);

        try {
            $this->peasantManager->createPeasant($peasantData);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The peasant was created successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The peasant was not created due to an exception.'
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
        $peasant = User::with('meta', 'profileImage', 'nonProfileImages')->where('id', $request->route('id'))->get()[0];

        return view(
            'admin.peasants.edit',
            [
                'title' => 'Edit Peasant - '. $peasant['username'] . '(ID: '. $peasant['id'] .') - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Edit',
                'carbonNow' => Carbon::now(),
                'peasant' => $peasant
            ]
        );
    }

    /**
     * @param PeasantUpdateRequest $peasantUpdateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PeasantUpdateRequest $peasantUpdateRequest)
    {
        $peasantUpdateRequest->formatInput();
        $peasantData = $peasantUpdateRequest->all();

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantUpdateRequest->route('id'));

            $alerts[] = [
                'type' => 'success',
                'message' => 'The peasant was updated successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The peasant was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    public function messagePeasantAsBot(int $peasantId, bool $onlyOnlineBots = false)
    {
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $onlineBotIds = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereIn('id', $onlineIds)
            ->get()->pluck('id')->toArray();

        $botsQueryBuilder = User::with('meta', 'roles', 'profileImage')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            });

        if ($onlyOnlineBots) {
            $botsQueryBuilder->whereIn('id', $onlineBotIds);
        }

        return view(
            'admin.peasants.message-as-bot',
            [
                'title' => 'Message Peasant as Bot- ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'Message peasant as bot',
                'carbonNow' => Carbon::now(),
                'peasant' => User::with('meta', 'profileImage')->find($peasantId),
                'bots' => $botsQueryBuilder
                    ->get(),
                'onlineBotIds' => $onlineBotIds
            ]
        );
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
