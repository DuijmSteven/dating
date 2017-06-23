<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    protected $leftSidebar;
    protected $rightSidebar;
    protected $sidebarCount;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setupSidebars();
    }

    /**
     * @param bool $leftSidebar
     * @param bool $rightSidebar
     */
    protected function setupSidebars(bool $leftSidebar = false, bool $rightSidebar = true): void
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
