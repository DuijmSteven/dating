<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ModuleInstances\ModuleInstancesUpdateRequest;
use App\LayoutPart;
use App\LayoutPartView;
use App\Module;
use App\ModuleInstance;
use App\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Exception\NotFoundException;

/**
 * Class ModuleController
 * @package App\Http\Controllers\Admin
 */
class ModuleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $modules = Module::orderBy('id', 'asc')->paginate(10);

        return view(
            'admin.modules.overview',
            [
                'title' => 'Modules Overview - ' . \config('app.name'),
                'headingLarge' => 'Modules',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'modules' => $modules
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLayout()
    {
        $views = View::with(['moduleInstances', 'layoutParts', 'moduleInstances.module'])->get();
        $modules = Module::all();
        $layoutParts = LayoutPart::all();

        return view(
            'admin.modules.layout',
            [
                'title' => ' - ' . \config('app.name'),
                'headingLarge' => 'Layout',
                'headingSmall' => '',
                'carbonNow' => Carbon::now(),
                'views' => $views,
                'modules' => $modules,
                'layoutParts' => $layoutParts
            ]
        );
    }

    /**
     * @param ModuleInstancesUpdateRequest $request
     * @param int $layoutPartId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateModules(ModuleInstancesUpdateRequest $request)
    {
        $request->removeNull();

        \DB::beginTransaction();

        try {
            \DB::table('module_instances')->delete();
            \DB::table('layout_part_view')->delete();

            foreach ($request->get('views') as $viewId => $layoutParts) {
                foreach ($layoutParts as $layoutPartId => $modules) {
                    $layoutPartActiveOnView = isset($modules['active']) && $modules['active'] == 'on' ? true : false;

                    if ($layoutPartActiveOnView) {
                        unset($modules['active']);
                        LayoutPartView::create([
                            'view_id' => $viewId,
                            'layout_part_id' => $layoutPartId,
                        ]);
                    }

                    foreach ($modules as $moduleId => $module) {
                        $moduleIsActiveOnLayoutPart = isset($module['active']) && $module['active'] == 'on' ? true : false;

                        if ($moduleIsActiveOnLayoutPart) {
                            ModuleInstance::create([
                                'view_id' => $viewId,
                                'layout_part_id' => $layoutPartId,
                                'module_id' => $moduleId,
                                'priority' => $module['priority']
                            ]);
                        }
                    }
                }
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();

            $alerts[] = [
                'type' => 'error',
                'message' => 'The modules could not be updated due to an exception.'
            ];

            \Log::error($exception->getMessage());

            return redirect()->back()->with('alerts', $alerts)->withInput(Input::all());
        }

        $alerts[] = [
            'type' => 'success',
            'message' => 'The modules were updated successfully.'
        ];

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRightSidebar()
    {
        $modules = Module::orderBy('name', 'desc');

        return view(
            'admin.modules.right-sidebar',
            [
                'title' => 'Modules Overview - ' . \config('app.name'),
                'headingLarge' => 'Modules',
                'headingSmall' => 'Right Sidebar',
                'carbonNow' => Carbon::now(),
                'modules' => $modules
            ]
        );
    }

    /**
     * @param int $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $moduleId)
    {
        try {
            Module::destroy($moduleId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The module was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The module was not deleted due to an exception.'
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
            'admin.modules.create',
            [
                'title' => 'Create module - ' . \config('app.name'),
                'headingLarge' => 'Modules',
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
            'admin.modules.edit',
            [
                'title' => 'Edit module - ' . \config('app.name'),
                'headingLarge' => 'Modules',
                'headingSmall' => 'Edit',
                'module' => Module::find($moduleId)
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
            Module::create($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The module was created.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The module was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param LayoutPartCreateRequest $request
     * @param int $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LayoutPartUpdateRequest $request, int $moduleId)
    {
        try {
            $module = Module::find($moduleId);

            $module->update($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The module was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The module was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @return \Closure
     */
    private function filterLayoutPart(string $layoutPartId): \Closure
    {
        return function ($query) use ($layoutPartId) {
            $query->where('id', $layoutPartId);
        };
    }
}
