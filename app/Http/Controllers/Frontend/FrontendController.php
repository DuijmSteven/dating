<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\View;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    protected $leftSidebar;
    protected $rightSidebar;
    protected $sidebarCount;
    protected $viewId = null;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $leftSidebar = false;
        $rightSidebar = false;

        if (!is_null(request()->route())) {
            $view = View::with('layoutParts')->where('route_name', request()->route()->getName())->get();

            if (!empty($view)) {
                $layoutPartIds = $view[0]->layoutParts->pluck('id')->toArray();
                $leftSidebar = in_array(1, $layoutPartIds);
                $rightSidebar = in_array(2, $layoutPartIds);
            }
        }

        $this->setupSidebars($leftSidebar, $rightSidebar);
    }

    /**
     * @param bool $leftSidebar
     * @param bool $rightSidebar
     */
    protected function setupSidebars(bool $leftSidebar, bool $rightSidebar): void
    {
        $this->leftSidebar = $leftSidebar;
        $this->rightSidebar = $rightSidebar;
        $this->sidebarCount = (int) $this->leftSidebar + (int) $this->rightSidebar;

        $this->shareSidebarsToViews();
    }

    private function shareSidebarsToViews(): void
    {
        \View::share('leftSidebar', $this->leftSidebar);
        \View::share('rightSidebar', $this->rightSidebar);
        \View::share('sidebarCount', $this->sidebarCount);
    }
}
