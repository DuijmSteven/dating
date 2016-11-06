<?php

namespace App\Http\Controllers\frontend;

use App\Managers\PeasantManager;
use App\Peasant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeasantController extends Controller
{
    /** @var PeasantManager  */
    private $peasantManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PeasantManager $peasantManager)
    {
        $this->peasantManager = $peasantManager;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $peasant = Peasant::findOrFail($id);

        $viewData = [
            'peasant' => $peasant,
            'carbonNow' => Carbon::now()
        ];

        return view(
            'frontend/peasants/profile',
            array_merge(
                $viewData,
                [
                    'title' => 'Profile - '. $peasant->username
                ]
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}