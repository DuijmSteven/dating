<?php

namespace App\ViewComposers\Frontend;

use Illuminate\View\View;

/**
 * Class RightSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class RightSidebarComposer extends LayoutPartComposer
{
    /** @var string */
    private $rightSidebarHtml;

    /**
     * RightSidebarComposer constructor.
     */
    public function __construct()
    {
        $this->rightSidebarHtml = $this->layoutPartHtml(2);
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('rightSidebarHtml', $this->rightSidebarHtml);
    }
}
