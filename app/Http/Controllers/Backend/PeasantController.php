<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Peasants\PeasantCreateRequest;
use App\Http\Requests\Backend\Peasants\PeasantUpdateRequest;
use App\Managers\PeasantManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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
        $peasants = User::with(['meta', 'roles', 'profileImage'])->whereHas('roles', function ($query) {
            $query->where('name', 'peasant');
        })->paginate(10);

        return view(
            'backend.peasants.index',
            [
                'title' => 'Peasant Overview - ' . \MetaConstants::$siteName,
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Overview',
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
            'backend.peasants.create',
            [
                'title' => 'Create Peasant - ' . \MetaConstants::$siteName,
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
        $peasant = User::with('meta')->where('id', $request->route('id'))->get()[0]->format();

        return view(
            'backend.peasants.edit',
            [
                'title' => 'Edit Peasant - '. $peasant['username'] . '(ID: '. $peasant['id'] .') - ' . \MetaConstants::$siteName,
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
