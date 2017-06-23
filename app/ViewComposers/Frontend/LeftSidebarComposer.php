<?php

namespace App\ViewComposers\Frontend;

use App\Managers\UserManager;
use Illuminate\View\View;

/**
 * Class LeftSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class LeftSidebarComposer extends LayoutPartComposer
{
    /** @var string */
    private $leftSidebarHtml;

    /** @var UserManager */
    private $userManager;

    /**
     * LeftSidebarComposer constructor.
     */
    public function __construct()
    {
        $this->leftSidebarHtml = $this->layoutPartHtml('left-sidebar');
        //parent::__construct();
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'leftSidebarHtml' => $this->leftSidebarHtml
        ]);
    }
}
