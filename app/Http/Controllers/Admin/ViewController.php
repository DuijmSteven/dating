<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Views\ViewCreateRequest;
use App\Http\Requests\Admin\Views\ViewUpdateRequest;
use App\View;
use Carbon\Carbon;

/**
 * Class ViewController
 * @package App\Http\Controllers\Admin
 */
class ViewController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview()
    {
        $views = View::orderBy('route_name', 'asc')->paginate(10);

        return view(
            'admin.views.overview',
            [
                'title' => 'Views Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Views',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'views' => $views
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreate()
    {
        return view(
            'admin.views.create',
            [
                'title' => 'Create view - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Views',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param ViewCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(ViewCreateRequest $request)
    {
        try {
            View::create([
                'name' => $request->getName(),
                'route_name' => $request->getRouteName()
            ]);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The view was created successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The view was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param int $viewId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $viewId)
    {
        try {
            View::destroy($viewId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The view was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The view was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }


    /**
     * @param int $viewId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ViewUpdateRequest $request, int $viewId)
    {
        try {
            View::find($viewId)->update([
                'name' => $request->getName(),
                'route_name' => $request->getRouteName(),
            ]);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The view was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => $exception->getMessage()
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }


    /**
     * @param int $viewId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showUpdate(int $viewId)
    {
        try {
            $view = View::findOrFail($viewId);

            return view(
                'admin.views.update',
                [
                    'title' => 'Create view - ' . ucfirst(\config('app.name')),
                    'headingLarge' => 'Views',
                    'headingSmall' => 'Edit',
                    'view' => $view,
                ]
            );
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => $exception->getMessage()
            ];

            return redirect()->back()->with('alerts', $alerts);
        }
    }
}
