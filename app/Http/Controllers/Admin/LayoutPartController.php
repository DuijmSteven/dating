<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayoutParts\LayoutPartModulesUpdateRequest;
use App\Http\Requests\Admin\Modules\ModuleUpdateRequest;
use App\LayoutPart;
use App\Module;
use App\Http\Requests\Admin\Modules\ModuleCreateRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

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
        $modules = Module::orderBy('name', 'desc')->paginate(10);

        return view(
            'admin.modules.index',
            [
                'title' => 'Modules Overview - ' . \MetaConstants::$siteName,
                'headingLarge' => 'Modules',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'modules' => $modules
            ]
        );
    }

    /**
     * @param LayoutPartModulesUpdateRequest $request
     * @param int $layoutPartId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateModules(LayoutPartModulesUpdateRequest $request, int $layoutPartId)
    {
        $modules = [];
        foreach ($request->get('modules') as $moduleId => $module) {
            $modules[$moduleId] = [
                'active' => isset($module['active']) ? 1 : 0,
                'priority' => $module['priority']
            ];
        }

        foreach ($modules as $moduleId => $module) {
            if ($module['active']) {
                LayoutPart::findOrFail($layoutPartId)->modules()->sync([$moduleId], false);
                LayoutPart::find($layoutPartId)->modules()->updateExistingPivot($moduleId, [
                    'priority' => $module['priority']
                ]);
            } else {
                LayoutPart::find($layoutPartId)->modules()->detach($moduleId);
            }
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
                'title' => 'Modules Overview - ' . \MetaConstants::$siteName,
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
                'title' => 'Create module - ' . \MetaConstants::$siteName,
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
                'title' => 'Edit module - ' . \MetaConstants::$siteName,
                'headingLarge' => 'Modules',
                'headingSmall' => 'Edit',
                'module' => Module::find($moduleId)
            ]
        );
    }

    /**
     * @param ModuleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(ModuleCreateRequest $request)
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
     * @param ModuleCreateRequest $request
     * @param int $moduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ModuleUpdateRequest $request, int $moduleId)
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
    private function filterModuleName(string $moduleName): \Closure
    {
        $filterModuleName = function ($query) use ($moduleName) {
            $query->where('name', $moduleName);
        };
        return $filterModuleName;
    }
}
