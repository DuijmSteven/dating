<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Modules\ModuleUpdateRequest;
use App\Module;
use App\Http\Requests\Admin\Modules\ModuleCreateRequest;
use Carbon\Carbon;

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
}
