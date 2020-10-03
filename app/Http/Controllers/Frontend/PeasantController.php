<?php

namespace App\Http\Controllers\Frontend;

use App\Managers\PeasantManager;
use App\Services\OnlineUsersService;
use Illuminate\Http\Request;

/**
 * Class PeasantController
 * @package App\Http\Controllers\Frontend
 */
class PeasantController extends FrontendController
{
    /** @var PeasantManager  */
    private $peasantManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PeasantManager $peasantManager,
        OnlineUsersService $onlineUsersService
    ) {
        $this->peasantManager = $peasantManager;
        parent::__construct($onlineUsersService);
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
