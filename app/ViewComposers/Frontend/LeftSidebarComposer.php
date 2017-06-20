<?php

namespace App\ViewComposers\Frontend;

use Illuminate\View\View;

/**
 * Class LeftSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class LeftSidebarComposer
{
    /** @var string */
    private $leftSidebarHtml;

    /**
     * LeftSidebarComposer constructor.
     */
    public function __construct()
    {
        $this->leftSidebarHtml = \View::make('frontend.components.user-activity')->render();
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('leftSidebarHtml', $this->leftSidebarHtml);
    }
}