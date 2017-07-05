<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayoutParts\LayoutPartUpdateRequest;
use App\LayoutPart;
use App\Http\Requests\Admin\LayoutParts\LayoutPartCreateRequest;
use Carbon\Carbon;

/**
 * Class LayoutPartController
 * @package App\Http\Controllers\Admin
 */
class LayoutPartController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $layoutParts = LayoutPart::orderBy('id', 'asc')->paginate(10);

        return view(
            'admin.layout-parts.index',
            [
                'title' => 'Layout Parts Overview - ' . \config('app.name'),
                'headingLarge' => 'Layout Parts',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'layoutParts' => $layoutParts
            ]
        );
    }

    /**
     * @param int $layoutPartId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $layoutPartId)
    {
        try {
            LayoutPart::destroy($layoutPartId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The layout part was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The layout part was not deleted due to an exception.'
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
            'admin.layout-parts.create',
            [
                'title' => 'Create layout Part - ' . \config('app.name'),
                'headingLarge' => 'Layout Part',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param int $moduleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $moduleId)
    {
        return view(
            'admin.layout-parts.edit',
            [
                'title' => 'Edit Layout Part - ' . \config('app.name'),
                'headingLarge' => 'Modules',
                'headingSmall' => 'Edit',
                'module' => LayoutPart::find($moduleId)
            ]
        );
    }

    /**
     * @param LayoutPartCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(LayoutPartCreateRequest $request)
    {
        try {
            LayoutPart::create($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The layout part was created.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The layout part was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param LayoutPartUpdateRequest $request
     * @param int $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LayoutPartUpdateRequest $request, int $layoutPartId)
    {
        try {
            $layoutPart = LayoutPart::find($layoutPartId);

            $layoutPart->update($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The layout part was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The layout part was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
