<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    private $hasLeftSidebar;
    private $hasRightSidebar;

    /**
     * Controller constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->setupSidebars();
    }

    private function setupSidebars(): void
    {
        $this->hasLeftSidebar = false;
        $this->hasRightSidebar = true;
        
        $this->shareSidebarVarsToViews();
    }

    private function shareSidebarVarsToViews(): void
    {
        \View::share('hasLeftSidebar', $this->hasLeftSidebar);
        \View::share('hasRightSidebar', $this->hasRightSidebar);
    }
}
