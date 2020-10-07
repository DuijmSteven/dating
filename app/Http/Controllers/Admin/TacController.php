<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tacs\TacCreateRequest;
use App\Http\Requests\Admin\Tacs\TacUpdateRequest;
use App\Tac;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;

/**
 * Class TacController
 * @package App\Http\Controllers\Admin
 */
class TacController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $tacs = Tac::all();

        foreach ($tacs as $tac) {
            $tac->content = Markdown::convertToHtml($tac->content);
        }

        return view(
            'admin.tacs.overview',
            [
                'title' => 'Tac Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Tac',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'tacs' => $tacs
            ]
        );
    }

    /**
     * @param int $tacId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $tacId)
    {
        try {
            Tac::destroy($tacId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The tac was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The tac was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param int $tacId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $tacId)
    {
        return view(
            'admin.tacs.edit',
            [
                'title' => 'Edit tac - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Tacs',
                'headingSmall' => 'Edit',
                'tac' => Tac::find($tacId)
            ]
        );
    }

    /**
     * @param TacUpdateRequest $request
     * @param int $tacId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TacUpdateRequest $request, int $tacId)
    {
        try {
            $tac = Tac::find($tacId);
            $tac->update($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The tac was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The tac was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param TacCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(TacCreateRequest $request)
    {
        try {
            Tac::create($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The tac was created.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The tac was not created due to an exception.'
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
            'admin.tacs.create',
            [
                'title' => 'Create tac - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Tacs',
                'headingSmall' => 'Create'
            ]
        );
    }
}
